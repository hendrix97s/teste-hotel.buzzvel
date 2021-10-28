<br> <br>

<div align="center">  
    <img alt="Buzzvel" height="100" src="https://github.com/hendrix97s/teste-hotel.buzzvel/blob/main/public/buzzvel.png">
</div>

<br><br>

##

### Informações do projeto:

**Descrição:** O projeto TH Buzzvel tem como objetivo retornar os hotéis mais próximos de um ponto de origem indicado pelo usuário ordenando-os por proximidade ou preço por noite de acordo com a preferência do mesmo.

**Tecnologias utilizadas no projeto:** Docker, Git,  Composer, PHP7, Mysql, Laravel 7, Vue.js e Boostrap. 

**Integrações:** Google Maps (geocode e distancematrix) e endpoint de listagem de hotéis da Buzzvel.     

 <div align="center">
  <code><img alt="PHP"        height="40" src="http://icons.luizlima.online/php/php-original.svg"></code>
  <code><img alt="JavaScript" height="40" src="http://icons.luizlima.online/javascript/javascript-original.svg"></code>
  <code><img alt="HTML5"      height="40" src="http://icons.luizlima.online/html5/html5-plain.svg"></code>
  <code><img alt="CSS3"       height="40" src="http://icons.luizlima.online/css3/css3-plain.svg"></code>
  <code><img alt="VueJS"      height="40" src="http://icons.luizlima.online/vuejs/vuejs-original.svg"></code>
  <code><img alt="Laravel"    height="40" src="http://icons.luizlima.online/laravel/laravel-plain.svg"></code>
  <code><img alt="mySQL"      height="40" src="http://icons.luizlima.online/mysql/mysql-plain.svg"></code>
  <code><img alt="Docker"     height="40" src="http://icons.luizlima.online/docker/docker-original.svg"></code>
  <code><img alt="Composer"   height="40" src="http://icons.luizlima.online/composer/composer-original.svg"></code>
  <code><img alt="Bootstrap"  height="40" src="http://icons.luizlima.online/bootstrap/bootstrap-plain.svg"></code>
  <code><img alt="Ubuntu"     height="40" src="http://icons.luizlima.online/ubuntu/ubuntu-plain.svg"></code>
 </div>




### Configurando ambiente Docker.

Primeiramente, renomeie o arquivo ".evn-example" da raiz do projeto para ".env".<br/> 
**Em seguida execute o comando docker abaixo pelo terminal na raiz do projeto para configurar o ambiente de desenvolvimento Docker:**

`$ docker-compose build app`

**Após a execução do comando, deverá exibir no terminal o trecho abaixo:**

```
----------------------------------------------------------------------

Build complete.
Don't forget to run 'make test'.

Installing shared extensions:     /usr/local/lib/php/extensions/no-debug-non-zts-20190902/
Installing header files:          /usr/local/include/php/
find . -name \*.gcno -o -name \*.gcda | xargs rm -f
find . -name \*.lo -o -name \*.o | xargs rm -f
find . -name \*.la -o -name \*.a | xargs rm -f
find . -name \*.so | xargs rm -f
find . -name .libs -a -type d|xargs rm -rf
rm -f libphp.la      modules/* libs/*
Removing intermediate container 9bb8578c9664
 ---> acaa80d71586
Step 7/11 : COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 ---> 325095429e65
Step 8/11 : RUN useradd -G www-data,root -u $uid -d /home/$user $user
 ---> Running in 8d6061ea6627
Removing intermediate container 8d6061ea6627
 ---> 512a433ad1cf
Step 9/11 : RUN mkdir -p /home/$user/.composer &&     chown -R $user:$user /home/$user
 ---> Running in 683d834a3fa0
Removing intermediate container 683d834a3fa0
 ---> 7c595f8898ae
Step 10/11 : WORKDIR /var/www
 ---> Running in 19ec3bc4096d
Removing intermediate container 19ec3bc4096d
 ---> 3d8cce4acdcf
Step 11/11 : USER $user
 ---> Running in a75f684d2da2
Removing intermediate container a75f684d2da2
 ---> d65b0dc12158

Successfully built d65b0dc12158
Successfully tagged teste-hotel.buzzvel:latest

```

**Para executar o ambiente de desenvolvimento, digite o comando docker abaixo:**

```
$ docker-compose up -d
```

**Saída:**
```
Creating network "teste_teste-hotel.buzzvel" with driver "bridge"
Creating teste-hotel.buzzvel-db    ... done
Creating teste-hotel.buzzvel-nginx ... done
Creating teste-hotel.buzzvel-app   ... done

```

**Isso executará seus contêineres em segundo plano. Para mostrar informações sobre o estado de seus serviços ativos, execute:**

`$ docker-compose ps`

Você verá um resultado como este:

```
Output
      Name                     Command               State                    Ports                  
-----------------------------------------------------------------------------------------------------
teste-hotel.buzzvel-app     docker-php-entrypoint php-fpm    Up      9000/tcp                                
teste-hotel.buzzvel-db      docker-entrypoint.sh mysqld      Up      0.0.0.0:3306->3306/tcp,:::3306->3306/tcp
teste-hotel.buzzvel-nginx   /docker-entrypoint.sh ngin ...   Up      0.0.0.0:8000->80/tcp,:::8000->80/tcp    

```

**Agora, seu ambiente está funcionando! Porém, ainda precisaremos executar alguns comandos para concluir a configuração do aplicativo. Você pode usar o comando docker-compose exec para executar comandos nos contêineres de serviço, como um ls -l para exibir informações detalhadas sobre os arquivos no diretório do aplicativo:**

`$ docker-compose exec app ls -l`

```
Output
total 1368
-rwxrwxr-x 1 luiz luiz     736 Oct  9 14:39 Dockerfile
-rw-rw-r-- 1 luiz luiz    3738 Oct  9 14:39 README.md
drwxrwxr-x 8 luiz luiz    4096 Oct  9 14:39 app
-rwxrwxr-x 1 luiz luiz    1686 Oct  9 14:39 artisan
drwxrwxr-x 3 luiz luiz    4096 Oct  9 14:39 bootstrap
-rw-rw-r-- 1 luiz luiz    1871 Oct  9 14:39 composer.json
-rw-rw-r-- 1 luiz luiz  273175 Oct  9 14:39 composer.lock
drwxrwxr-x 2 luiz luiz    4096 Oct  9 14:39 config
drwxrwxr-x 5 luiz luiz    4096 Oct  9 14:39 database
drwxrwxr-x 4 luiz luiz    4096 Oct  9 14:39 docker-compose
-rw-rw-r-- 1 luiz luiz    1025 Oct  9 15:28 docker-compose.yml
-rw-rw-r-- 1 luiz luiz 1048175 Oct  9 14:39 package-lock.json
-rw-rw-r-- 1 luiz luiz    1161 Oct  9 14:39 package.json
-rw-rw-r-- 1 luiz luiz    1215 Oct  9 14:39 phpunit.xml
drwxrwxr-x 6 luiz luiz    4096 Oct  9 14:39 public
drwxrwxr-x 6 luiz luiz    4096 Oct  9 14:39 resources
drwxrwxr-x 2 luiz luiz    4096 Oct  9 14:39 routes
-rw-rw-r-- 1 luiz luiz     563 Oct  9 14:39 server.php
drwxrwxr-x 5 luiz luiz    4096 Oct  9 14:39 storage
drwxrwxr-x 4 luiz luiz    4096 Oct  9 14:39 tests
-rw-rw-r-- 1 luiz luiz     538 Oct  9 14:39 webpack.mix.js

```

## Instalando as dependências do projeto

**Execute o composer install para instalar as dependências do aplicativo:**

```$ docker-compose exec app composer install```

Você verá um resultado como este:

```
Output
Generating optimized autoload files
> Illuminate\Foundation\ComposerScripts::postAutoloadDump
> @php artisan package:discover --ansi
Discovered Package: facade/ignition
Discovered Package: fideloper/proxy
Discovered Package: fruitcake/laravel-cors
Discovered Package: laravel/tinker
Discovered Package: laravel/ui
Discovered Package: nesbot/carbon
Discovered Package: nunomaduro/collision
Package manifest generated successfully.
76 packages you are using are looking for funding.
Use the `composer fund` command to find out more!

```

### Gerando nova key Laravel:

A última coisa que precisamos fazer antes de testar o aplicativo é gerar uma chave única para o aplicativo com a artisan, a ferramenta de linha de comando do Laravel. Esta chave é usada para criptografar sessões de usuário e outros dados sensíveis:

```$ docker-compose exec app php artisan key:generate```

```
Output
Application key set successfully.
```

### Preparando aplicação para uso:

Execute o comando abaixo para que o artisan do laravel configure o banco de dados e popule a tabela hotel a partir do seeder configurado

```$ docker-compose exec app php artisan migrate --seed```

**Obs. O comando de migrate levara um tempo para finalizar sua execução, pois além de criar as tabelas necessárias para o funcionamento do siste, será executado o seeder responsável por popular a tabela hotel a partir do consumo da api buzzvel**


### Acessando o projeto pelo navegador:

Em uma nova aba no seu navegador acesse o nome de domínio ou endereço IP do seu servidor na porta 8000, ou clique no link abaixo:

[Clique aqui para acessar o projeto](http://localhost:8000)
