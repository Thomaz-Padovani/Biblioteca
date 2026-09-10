# Sistema de Gestão de Biblioteca — Projeto Semestral

Código de referência do projeto semestral de **Desenvolvimento Web II**, cobrindo o
stack completo definido para o semestre: **Laravel** (back-end/API) + **React**
(front-end) + **PostgreSQL** (banco de dados, hospedado no Supabase).

Este repositório existe para uso em sala de aula — mostrar código real de cada
conceito conforme os encontros avançam. Não é um scaffold vazio: os módulos abaixo
já têm migrations, relacionamentos, autenticação e regras de negócio implementados.

## Estrutura

```
biblioteca/
├── biblioteca-api/      # Back-end Laravel (API REST)
└── biblioteca-front/    # Front-end React (Vite)
```

## O que já está implementado

| Módulo | Cobre os tópicos de |
|---|---|
| Migrations (`autores`, `livros`, `categorias`, `usuarios`, `emprestimos`, `reservas`) | Encontro 3 — Persistência e Migrations |
| Models com `hasMany`, `belongsTo`, `belongsToMany`, scope local | Encontro 3 — Eloquent ORM e Relacionamentos |
| `AuthController` com Sanctum (register/login/logout) | Encontro 5 e Encontro 7 — Sessões e API RESTful |
| `LivroController`, `AutorController`, `CategoriaController` (CRUD completo) | Encontro 3 e Encontro 4 — CRUD e Controllers |
| `Form Requests` com validação e autorização por perfil | Encontro 4 — Validação com Form Request |
| `EmprestimoController` com regra de negócio (não permite emprestar livro já emprestado) | Encontro 3/7 — regras de negócio no Model/Controller |
| React com `AuthContext`, rotas protegidas, consumo via Axios | Encontro 6 e Encontro 8 — Assíncrono, SPA e integração |
| `Dockerfile` + `docker-compose.yml` do back-end | Encontro 2 e Encontro 15 — Ambientes e Containerização |
| Upload de capa via Laravel Storage + Intervention Image | Encontro 13 — Upload, Download e Manipulação de Arquivos |
| Testes de Feature (PHPUnit) e testes de componente (Vitest) | Encontro 16 — Qualidade de Código |
| React Helmet, `robots.txt`/`sitemap.xml`, code splitting, `React.memo`, `loading="lazy"` | Encontro 17 — SEO, Web Analytics e Performance |
| `render.yaml`, `vercel.json`, CORS de produção, entrypoint com migrations automáticas | Encontro 14 — Hospedagem e Deploy |
| `.github/workflows/ci.yml` — lint, testes e build automáticos a cada push | Encontro 16 — CI/CD |
| Prefixo `/v1`, `throttle` e rotas versionadas em `routes/api.php` | Encontro 4 — Rotas de API bem organizadas |
| `app/Services/EmprestimoService.php` e `DevolucaoController` (invokable) | Encontro 4 — Organizando Controllers |
| `app/Rules/IsbnValido.php` e validação condicional/aninhada em `StoreLivroRequest` | Encontro 4 — Validação Avançada |
| `app/Support/helpers.php` (`respostaErro()`) e `bootstrap/app.php` completo (erros de API sempre em JSON) | Encontro 4 — Respostas de API Consistentes |
| Migration `sessions` (driver `database`) | Encontro 5 — Sessões no Laravel |

Todos os tópicos do roteiro já têm uma implementação de referência neste projeto —
não há mais nenhum item propositalmente deixado de fora.

## Como rodar localmente

Veja o guia completo de instalação (`guia_instalacao_stack.docx`, já compartilhado)
para instalar PHP, Composer, Node e Docker.

### Back-end

Este repositório contém apenas o **código da aplicação** (`app/`, `database/`,
`routes/`, `composer.json` com as dependências extras) — não o esqueleto do
framework (`artisan`, `bootstrap/`, `public/`, `resources/`, `storage/`), que é
idêntico em qualquer projeto Laravel novo e por isso não foi incluído aqui.
Você precisa gerar esse esqueleto uma vez e mesclar com o que já está aqui:

```
# 1. gere o esqueleto do Laravel em uma pasta TEMPORÁRIA (separada desta)
cd ..
composer create-project laravel/laravel biblioteca-api-esqueleto

# 2. copie o esqueleto por cima da pasta deste projeto, SEM sobrescrever
#    os arquivos que já existem aqui

# macOS / Linux:
cp -rn biblioteca-api-esqueleto/. biblioteca-api/

# Windows (PowerShell):
robocopy biblioteca-api-esqueleto biblioteca-api /E /XC /XN /XO

# 3. confirme que o artisan chegou
cd biblioteca-api
ls artisan

# 4. reinstale as dependências (agora com o composer.json completo)
composer update

# 4b. limpe o cache de descoberta de pacotes — ele foi copiado do esqueleto
#     temporário e pode referenciar pacotes (ex.: laravel/pail) que não
#     estão no nosso composer.json, causando erro "Class ... not found"

# macOS / Linux:
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php

# Windows (PowerShell):
Remove-Item bootstrap\cache\packages.php, bootstrap\cache\services.php -ErrorAction SilentlyContinue

composer dump-autoload

# 5. finalize a configuração
cp .env.example .env
php artisan key:generate
# edite o .env com os dados do seu banco Supabase
php artisan migrate --seed
php artisan serve

# 6. pode apagar a pasta temporária
rm -rf ../biblioteca-api-esqueleto
```

> Se `php artisan` retornar "Could not open input file: artisan", é sinal de
> que o passo 1–2 não foi feito — você está tentando rodar comandos do
> Laravel numa pasta que só tem o código da aplicação, sem o framework em si.

### Front-end

```
cd biblioteca-front
npm install
cp .env.example .env
npm run dev
```

### Usuário de teste (criado pelo seeder)

```
email: bibliotecaria@biblioteca.test
senha: password
perfil: bibliotecario
```

## Rodando os testes

```
# back-end (usa SQLite em memória — configurado no phpunit.xml padrão do Laravel)
cd biblioteca-api
php artisan test

# front-end
cd biblioteca-front
npm run test
```

## Upload de capa de livro

Depois de configurar o `.env`, publique o link simbólico de storage uma vez:

```
php artisan storage:link
```

Sem isso, as imagens salvas em `storage/app/public` não ficam acessíveis pela URL pública.

## CI/CD com GitHub Actions

O workflow em `.github/workflows/ci.yml` roda a cada push e pull request na branch
`main`, com três jobs:

1. **backend** — sobe um PostgreSQL de serviço, instala as dependências via Composer, roda o lint (`laravel/pint`) e a suíte de testes (`php artisan test`)
2. **frontend** — instala as dependências via npm, roda lint, os testes com Vitest, e o build de produção
3. **deploy-homologacao** — opcional; só roda na `main` e só se os dois jobs anteriores passarem. Dispara um [deploy hook](https://render.com/docs/deploy-hooks) do Render via `curl`

> Se o repositório do time for único (monorepo com `biblioteca-api/` e
> `biblioteca-front/` juntos), este arquivo já funciona como está. Se optarem
> por dois repositórios separados, copie o arquivo para cada um e remova o job
> que não se aplica.

Para o job de deploy funcionar, cadastre o secret `RENDER_DEPLOY_HOOK_URL` nas
configurações do repositório no GitHub (Settings → Secrets and variables →
Actions), com a URL do deploy hook gerada no painel do Render. **Isso é
opcional** — o Render já faz auto-deploy sozinho quando conectado ao GitHub;
esse job só faz sentido se vocês quiserem que o deploy só aconteça depois do
CI passar.

## Deploy em produção

### Back-end no Render

1. Suba o repositório do back-end no GitHub e conecte no Render como "New Web Service" (ou use o `render.yaml` deste repositório com "New Blueprint")
2. Preencha as variáveis de ambiente marcadas com `sync: false` no `render.yaml` (chave da aplicação, dados do Supabase, URL do front-end)
3. O `docker-entrypoint.sh` roda `migrate --force` automaticamente a cada deploy — não precisa rodar migration manualmente em produção

### Front-end no Vercel

1. Importe o repositório do front-end no Vercel
2. Configure a variável de ambiente `VITE_API_URL` apontando para a URL pública do back-end no Render (ex.: `https://biblioteca-api.onrender.com/api`)
3. O `vercel.json` já inclui o rewrite necessário para o React Router funcionar em qualquer rota, inclusive em recarregamentos de página

## Endpoints principais da API

| Método | Rota | Descrição |
|---|---|---|
| POST | /api/register | Cria um novo usuário |
| POST | /api/login | Autentica e retorna um token Sanctum |
| GET | /api/livros | Lista livros (aceita `?busca=` e `?disponiveis=1`) |
| POST | /api/livros | Cadastra um livro (requer perfil bibliotecário) |
| POST | /api/livros/{id}/emprestimos | Registra um empréstimo |
| PATCH | /api/emprestimos/{id}/devolucao | Registra a devolução |
| POST | /api/livros/{id}/capa | Envia/substitui a capa do livro (multipart, campo `capa`) |
| DELETE | /api/livros/{id}/capa | Remove a capa atual do livro |
