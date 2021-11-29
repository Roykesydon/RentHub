# DB_Project

## Config
    - docker-compose.yml
        environment:
          MARIADB_ROOT_PASSWORD: 自己填 -> 密碼
          MARIADB_DATABASE: db_final -> 資料庫名稱
          MARIADB_USER: 自己填 -> 帳戶
          MARIADB_PASSWORD: 自己填 -> 密碼
        
    - config.yml("/backend/initDB/config.yml")
        db:
            host : localhost
            user : 自己填(與上面一樣)
            password : 自己填(與上面一樣)
            database : db_final

## Backend
```
in DB_Project開啟服務(需先填完所有config):
docker-compose up

初始化資料庫(需先啟動服務):
cd backend/initDB/
pipenv install
pipenv run python initDB.py
```

## Port
 - 8000 : API
 - 8001 : PhpMyAdmin
 - 3306 : MariaDB