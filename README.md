<img src="public/assets/dist/img/banner_1.0.0.png" width="100%">

Desenvolvido em Laravel para controle de depesesas pessoais, usando como padrão o orçamento "50/35/15", sendo:

#### - 50% da receita, será destinado para despesas "Essenciais".
- Aluguel;
- Água;
- Energia;
- Internet;
- Educação;

#### - 35% da receita, será destinado para despesas de "Lazer".
- Serviços de Streaming;
- Diversão;
    
#### - 15% da receita, será destinado para Investimentos.

***
### Funções/Módulos
***
#### Despesas
- Todas as despesas são registradas tendo como informação o Banco/Conta utilizado, a forma de pagamento e o orçamento ao qual essa despesa pertence;
#### Entradas
- Todas as fontes de renda devem ser informadas para que sejam criados os orçamentos e indicadores na dashboard;

#### Transferências
- Caso seja necessário realizar alguma transferência entre contas de uma mesma pessoa, ou entre pessoas que seguem o mesmo orçamento. A mesma deverá ser inserida neste módulo, pois assim é possível acompanhar o saldo de cada conta bancária;

#### Investimentos
- Registro de investimentos realizados, sendo possível inserir no período que preferir o rendimento de cada um;

#### Faturas
- Caso haja utilização de Cartão de Crédito, será possível acompanhar os gastos por meio de uma "fatura pessoal";

#### Carteiras
- As carteiras tem como objetivo agrupar Cartões/Bancos/Dinheiro fisico de forma que possa ser compartilhado, ou não, com uma segunda pessoa que utilize o mesmo orçamento;

#### Bancos e Contas
- Todas as suas contas serão cadastradas aqui para informar despesas, investimento e etc.

#### Orçamento
- Caso deseje alterar o orçamento padrão do sistema;

***
### Screenshots
***

<p align="center">
  <img src="public/assets/screenshots/light_dash_1.0.0.png" width="100%" title="Light Dashboard">
  <img src="public/assets/screenshots/dark_dash_1.0.0.png" width="100%" title="Dark Dashboard">
</p>

***
### Configrando Servidor WEB
***
1. Install Apache   
    `$ sudo apt update`   
    `$ sudo apt install apache2`
   
2. Install MySQL Server   
    `$ sudo apt install mysql-server`
   
3. Install PHP     
    `$ sudo apt install php8.0 libapache2-mod-php8.0 php-mysql8.0`

***
### Configurando Banco de Dados
1. Crie um banco de dados MySQL para o projeto
    * ```mysql -u root -p```   
    * ```create database money_map;```
      
2. Crie o usuário e dê as permissões necessárias
    * ```CREATE USER 'money_map'@'localhost' IDENTIFIED BY 'your_password_here';```
    * ```GRANT ALL PRIVILEGES ON money_map.* TO 'money_map'@'localhost';```  
    * ```quit;```

***
### Configuração Final
***
1. Execute `git clone https://github.com/gelbcke/moneymap.git`
2. Vá para a pasta do projeto 
   * `cd /var/www/html/moneymap`
3. Da pasta raiz do projeto, execute
   * `sudo cp .env.example .env`
4. Configure seu arquivo `.env`
5. Da pasta raiz do projeto, execute
   * `composer install`
   * `php artisan key:generate`
   * `php artisan migrate`
   * `php artisan db:seed`
   * `php artisan optimize`  
   * `composer dump-autoload`
   
#### Set Folders and Files Permissions
   * ```sudo chmod -R 777 ./```   
   * ```sudo chown -R www-data:www-data ./```   
   * ```sudo find ./ -type f -exec chmod 644 {} \;```   
   * ```sudo find ./ -type d -exec chmod 755 {} \;```
   * ```sudo chgrp -R www-data storage bootstrap/cache```
   * ```sudo chmod -R ug+rwx storage bootstrap/cache```   
   * ```sudo chmod -R 777 ./bootstrap/cache/```

***
### Informações importantes
***
#### Credenciais do SEED
- Usuário: admin@moneymap.com
- Senha: secret

Exemplo do arquivo `.env`:
```
APP_NAME="Money MAP"
APP_ENV=local
APP_DEBUG=true
APP_KEY=SomeRandomString

DB_HOST=localhost
DB_DATABASE=money_map
DB_USERNAME=money_map
DB_PASSWORD=your_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

#### CRON Tasks
```
* * * * * php /var/www/html/moneymap/artisan schedule:run
```

***
##### FrontEnd by [ColorlibHQ - AdminLTE](https://github.com/ColorlibHQ/AdminLTE)