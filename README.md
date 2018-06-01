# message-board

+ ### Build for everyone!!<hr>
    + Our goal is to create a platform for everyone to talk with.
You can talk to people around the world by using message-board.
Sign up or Sign in to enjoy our service!!.

+ ### Getting started<hr>

    + #### Requirements
        + ubuntu 16.04 LTS
        + apache 2.4.18(Ubuntu)
        + php 7.0.30-0ubuntu.16.04.1 (cli) ( NTS )
        + mysql Ver 14.14 Distrib 5.7.22 for Linux(x86_64)

    + #### Clone repo
        ```shell=1
        git clone https://github.com/aeternum1234/message-board.git ~/Documents/www
        ```

    + #### Configure the apache web server
        ```shell=1
        sudo vim /etc/apache/apache2.conf
        ```
        change
        ```shell=1
        <Directory /var/www/>
            Options FollowSymLinks
            AllowOverride None
            Require all granted
        </Directory>
        ```
        to
        ```shell=1
        <Directory /home/YOUR_USERNAME/Documents/www/>
            Options FollowSymLinks
            AllowOverride None
            Require all granted
        </Directory>
        ```
        save and exit
        and
        ```shell=1
        sudo vim /etc/apache2/sites-enabled/000-default
        ```
        change
        ```shell=1
        DocumentRoot /var/www/
        ```
        to
        ```shell=1
        DocumentRoot /home/YOUR_USERNAME/Documents/www/
        ```
        save and exit and do
        ```shell=1
        sudo service apache2 restart
        ```
    + #### Configure the mysql database
        set mysql username = 'root' and password = '1234'

+ ### Running
    + just start your apache and mysql , and type 'localhost' in your web browser

+ ### License
    + This project is licensed under MIT License - see the [LICENSE](https://github.com/aeternum1234/message-board/blob/master/LICENSE) file for detail

+ ### Photo<hr>
    + index
    + ![](https://i.imgur.com/wEzEVYn.png)
    + ![](https://i.imgur.com/Ltv70kb.png)
    + account center
    + ![](https://i.imgur.com/depSOWA.png)
    + ![](https://i.imgur.com/P30U8jM.png)
