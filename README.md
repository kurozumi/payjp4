# PayJP for EC-CUBE4

EC-CUBE4でPay.JPでクレジットカード決済ができるプラグインのサンプルです。  
非公式プラグインですのでご利用は自己責任でお願い致します。  

サンプルプラグインは注文完了後、即売上確定になりますが、設定により仮売上にすることも可能です。  
詳しくはPay.JPのAPIリファレンスを参照ください。

[Charge (支払い)](https://pay.jp/docs/api/#charge-%E6%94%AF%E6%89%95%E3%81%84,)


EC-CUBEの注文データと課金データを紐付けための課金IDをEC-CUBE側に保存する処理を実装するのを忘れていたのでそのうち更新します。


## インストールと有効化

```
bin/console eccube:plugin:install --code PayJP
bin/console eccube:plugin:enable --code PayJP
```

## 秘密鍵と公開鍵を設定

Pay.JPのアカウントを取得して秘密鍵と公開鍵を以下のファイルに設定してください。

```
Plugin/PayJP/Resource/config/services.yaml
```

## Shopping/index.twigにタグを追記

Shopping/index.twigに以下のタグを追記してください。

```
{{ include('@PayJP/credit.twig', ignore_missing=true) }}
```

以上で設定は終了です。
お疲れさまでした。


あとは配送方法設定で取り扱う支払い方法にPayJPを追加してあげてください。
