<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RepositoryEstructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria a estrutura básica do Repository';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            # Obtem o nome do arquivo
            $name = $this->argument('name');
    
            # Cria o arquivo Repository
            $this->_createRepositoryFile($name);

            $this->info('Arquivo gerado com sucesso!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Cria o arquivo Repository
     * 
     * @param mixed $name 
     * @return void 
     */
    private function _createRepositoryFile($name)
    {
        $file = "";
        $file .= "<?php\n";
        $file .= "\n";
        $file .= "namespace App\\Repository;\n";
        $file .= "\n";
        $file .= "use App\\Models\\{$name};\n";
        $file .= "use App\Repository\BaseRepository;\n";
        $file .= "\n";
        $file .= "class {$name}Repository extends BaseRepository\n";
        $file .= "{\n";
        $file .= "    /**\n";
        $file .= "     * Create a new repository instance\n";
        $file .= "     * \n";
        $file .= "     * @param {$name} ".'$model'."\n";
        $file .= "     * @return void \n";
        $file .= "     */\n";
        $file .= "    public function __construct({$name} ".'$model'.")\n";
        $file .= "    {\n";
        $file .= "        ".'$this->model'." = ".'$model'.";\n";
        $file .= "    }\n";
        $file .= "}";

        $fp = \fopen("{$this->laravel->basePath()}/app/Repository/{$name}Repository.php", "w+");
        \fwrite($fp, $file);
        \fclose($fp);

        $this->info('Repository gerada com sucesso.');
    }
}