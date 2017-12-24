# ieServer_IP_updater
PHPをコマンドで実行してieServerのIPアドレスの監視〜更新を行うスクリプト

## 使い方
サーバにphpを入れてcronで叩く。
設定は以下

### account
ieServerのユーザー名

### domain
更新対象のドメイン名

### パスワード
ieServerのパスワード
今のところそのまんま記述

### interval
PHPの日付記述が通用する文字を入れる。  

### defaultTimezone
タイムゾーン。日本ならそのまんまでいい。

## 簡単な動き
cronで動かす前提。  
IPアドレスの変更、もしくはサーバがダウンしている場合に、標準出力が発生する。  
cronで動かす関係上、標準出力はそのままメールで送られる。  
intervalの期間を過ぎた場合に自動的に更新処理となるURLを実行して標準出力が出る。 
