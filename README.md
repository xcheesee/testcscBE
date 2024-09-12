# Backend Teste CSC

**Pre-requisitos**
* [PHP](https://www.php.net/)
* [Composer](https://getcomposer.org/)
* [MySQL](https://getcomposer.org/)
*  Ctype PHP Extension
*   cURL PHP Extension
*   DOM PHP Extension
*   Fileinfo PHP Extension
*   Filter PHP Extension
*   Hash PHP Extension
*   Mbstring PHP Extension
*   OpenSSL PHP Extension
*   PCRE PHP Extension
*   PDO PHP Extension
*   Session PHP Extension
*   Tokenizer PHP Extension
*   XML PHP Extension

## Instalacao

Navegue para o local que deseja clonar o repositório e execute o seguinte comando:
`git clone https://github.com/xcheesee/testcscBE`

Em seguida:
`cd testcscBE`

Já dentro do diretório do projeto, execute:
`composer install`

Realize uma cópia do arquivo .env.example, retirando o .example e configurando as seguintes variáveis
~~~
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<nome da database>
DB_USERNAME=<usuario mysql>
DB_PASSWORD=<senha mysql>
~~~

Certifique-se também de criar uma database com o mesmo nome do configurado em seu .env, com os seguintes comandos:
`mysql -u<usuario> -p<senha>`
`CREATE DATABASE <nome da database>`

Após a instalação das dependências, execute os seguintes comandos:
`php artisan key:generate`
`php artisan migrate`
`php artisan serve`

E então verifique que a API está live navegando para a rota http://localhost:8000/
