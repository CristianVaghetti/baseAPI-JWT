<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ValidationEstructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:validation {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria a estrutura básica de um Validation';

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
        $name = $this->argument('name');
        
        # Cria o arquivo service
        $this->_createValidationFile($name);
    }

    /**
     * Cria o arquivo Service
     * 
     * @param string $name 
     * @return void 
     */
    private function _createValidationFile($name)
    {
        $file = "";
        $file .= "<?php\n";
        $file .= "\n";
        $file .= "namespace App\\Http\\Validation;\n";
        $file .= "\n";
        $file .= "use App\\Helpers\\ResponseHelper;\n";
        $file .= "use App\\Http\\Validation\\IValidation;\n";
        $file .= "use App\\Repository\\{$name}Repository;\n";
        $file .= "use Illuminate\\Http\\JsonResponse;\n";
        $file .= "\n";
        $file .= "class {$name}Validation implements IValidation\n";
        $file .= "{\n";
        $file .= "    /**\n";
        $file .= "     * Repository of {$name}\n";
        $file .= "     *\n";
        $file .= "     * @var {$name}Repository\n";
        $file .= "     */\n";
        $file .= "    protected {$name}Repository ".'$repository'.";\n";
        $file .= "\n";
        $file .= "    /**\n";
        $file .= "     * Create a new validation instance.\n";
        $file .= "     *\n";
        $file .= "     * @param {$name}Repository ".'$repository'."\n";
        $file .= "     * @return void\n";
        $file .= "     */\n";
        $file .= "    public function __construct({$name}Repository ".'$repository'.")\n";
        $file .= "    {\n";
        $file .= "        ".'$this->repository'." = ".'$repository'.";\n";
        $file .= "    }\n";
        $file .= "\n";
        $file .= "    /**\n";
        $file .= "     * Make a busines validate\n";
        $file .= "     *\n";
        $file .= "     * @param array ".'$dados'."\n";
        $file .= "     * @param int|null ".'$id'."\n";
        $file .= "     * @return array|\\Illuminate\\Http\\JsonResponse\n";
        $file .= "     */\n";
        $file .= "    public function validate(array ".'$dados'.", ".'$id'." = 0): array | JsonResponse\n";
        $file .= "    {\n";
        $file .= "        return ResponseHelper::responseSuccess(json: false);\n";
        $file .= "    }\n";
        $file .= "\n";
        $file .= "}";

        $fp = \fopen("{$this->laravel->basePath()}/app/Http/Validation/{$name}Validation.php", "w+");
        \fwrite($fp, $file);
        \fclose($fp);

        $this->info('Validation gerado com sucesso.');
    }
}