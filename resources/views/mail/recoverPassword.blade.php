<h3>Olá {{$user->name}}, tudo bem?</h3>

<p>Você está recebendo este e-mail, porque recebemos uma solicitação de redefinição de senha para sua conta.</p>

<p>Clique aqui para redefinir <a href="{{$url}}/alterar-senha/{{$token->token}}">aqui</a></p>

<p>Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.</p>
