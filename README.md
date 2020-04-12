# PayJP for EC-CUBE4

EC-CUBE4でPay.JPでクレジットカード決済ができるプラグインのサンプルです。  
非公式プラグインですのでご利用は自己責任でお願い致します。  

サンプルプラグインは注文完了後、即売上確定になりますが、設定により仮売上にすることも可能です。  
詳しくはPay.JPのAPIリファレンスを参照ください。

[Charge (支払い)](https://pay.jp/docs/api/#charge-%E6%94%AF%E6%89%95%E3%81%84,)


## インストールと有効化

```
bin/console eccube:composer:require payjp/payjp-php

bin/console eccube:plugin:install --code PayJP
bin/console eccube:plugin:enable --code PayJP
```

## 秘密鍵と公開鍵を設定

Pay.JPのアカウントを取得して秘密鍵と公開鍵を環境変数(.env)に設定してください。

```
## 公開キー
PAYJP_PUBLIC_KEY=pk_test_b953b765fac7c8a80541e968
## シークレットキー
PAYJP_SECRET_KEY=sk_test_c7c3078e9f71ab0ce9d59371
```

## Shopping/index.twigにタグを追記

Shopping/index.twigに以下のタグを追記してください。

```
{{ include('@PayJP/credit.twig', ignore_missing=true) }}
```

以上で設定は終了です。
お疲れさまでした。


あとは配送方法設定で取り扱う支払い方法にPayJPを追加してあげてください。
