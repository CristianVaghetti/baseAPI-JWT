<x-mail::message>
# Redefinir senha

Olá, {{ $user->name }}

Foi criado um usuário de acesso ao nosso sistema.

Para acessar o nosso sistema, clique no link abaixo.<br/>
<a href="{{ $url }}">Alterar minha senha</a>

Para sua segurança, será necessário alterar a senha no primeiro acesso.

O link possui validade de 48 horas. Após esse período, ele estará indisponível. Esta é uma mensagem automática. Por favor, não responda este e-mail.

Atenciosamente,<br/>Eu

</x-mail::message>
