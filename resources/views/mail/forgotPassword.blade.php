<x-mail::message>
Olá, {{ $user->name ?? 'NOME' }}<br/>
Você esqueceu sua senha? Nós te ajudamos.

Clique no botão abaixo para redefinir sua senha.<br/>
<x-mail::button :url="$url">
Redefinir
</x-mail::button>

Este e-mail possui validade de 48 horas. Após esse período, esta opção estará indisponível.<br/>
Caso você não tenha esquecido sua senha, desconsidere este e-mail.

Esta é uma mensagem automática. Por favor, não responda este e-mail.

Atenciosamente,<br/>Eu

</x-mail::message>
