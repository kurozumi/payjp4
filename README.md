# PayJp for EC-CUBE4

EC-CUBE4でPay.JPでクレジットカード決済ができるプラグインのサンプルです。  
非公式プラグインですのでご利用は自己責任でお願い致します。  

サンプルプラグインは注文完了後、即売上確定になりますが、設定により仮売上にすることも可能です。  
詳しくはPay.JPのAPIリファレンスを参照ください。

[Charge (支払い)](https://pay.jp/docs/api/#charge-%E6%94%AF%E6%89%95%E3%81%84,)


## インストールと有効化

```
bin/console eccube:composer:require payjp/payjp-php

bin/console eccube:plugin:install --code payjp4
bin/console eccube:plugin:enable --code payjp4
```

## 秘密鍵と公開鍵を設定

Pay.JPのアカウントを取得して秘密鍵と公開鍵を管理画面で設定してください。

![PAY.JP管理画面](https://github.com/kurozumi/payjp4/blob/images/payjp_config.png)

## 配送方法設定でPAY.JPを追加

配送方法設定ページで取り扱う支払い方法にPayJPを追加してあげてください。
