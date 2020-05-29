### 使用说明

```php

$client = (new MT5Client())->setHost('127.0.0.1')->setPort(9090)->instantiation()->open();
echo $client->Version();
$client->close();

```

### 生成依赖文件

- 生成 php 依赖文件

```
./thrift-0.10.0.exe --gen php -o ./src mt5service.thrift
```