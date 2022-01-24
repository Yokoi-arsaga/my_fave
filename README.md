### 環境構築

``` bash
git clone git@github.com:Yokoi-arsaga/my_fave.git

cd my_fave

make create-project
```

### バージョン情報

|要素技術|バージョン|
|----|----|
| php | 8.0.14 |
| laravel | 8.78.1 |
| nginx | 1.20.1 |
| Mysql | 8.0.27 |
| WordPress | 5.8.2 |

### ブランチ運用
必ず[Wiki](https://github.com/Yokoi-arsaga/my_fave/wiki)を確認してください！！

### 推奨プラグイン
このプロジェクトでは以下のプラグインの使用を推奨しています。
レベルにかかわらず導入することでコードの品質がぐっと上がるので導入をお勧めします！
https://www.notion.so/arsagajp/38df716b14894b5980da444b10fb5a6a

laravel-ide-helperについて
Laravelは内部でマジックメソッドが多様されている都合上、補完が効かないことによる打ち間違いやnull考慮漏れの バグが発生しやすいフレームワークです。そんなLaravelの補完を改善してくれるのが、 この「laravel-ide-helper」になります。

このツールを使うと、リレーションやFacadeのクラス、 Modelのfield変数の型の補完などが効くようになり、 実装自体が楽になり、上記プラグインと組み合わせればnullの考慮漏れなんかもかなり減らすことができます。

スムーズなプロジェクト運営のためにもこちらのツールの利用をお願いします。

使用方法
php artisan ide-helper:generate            # PHPDoc generation for Laravel Facades
php artisan ide-helper:models --nowrite    # PHPDocs for models
php artisan ide-helper:meta                # PhpStorm Meta file
「ide-helper:models」に関してはModelの追加があった時には再度実行しなおしてください。 また、データベースと接続していないと実行できないため、appコンテナ内で上記コマンドを実行してください

注意点
「ide-helper:models」のコマンドには２種類の動作が存在し、Modelファイルに直接型を埋める処理と、 別ファイルに型を指定する処理があります。前者はコードがかなり汚れてしまうので、 後者の動作を利用するようにしてください。 （--nowrite オプションをつけた場合は強制的に後者の動作を利用します）

--nowriteオプションをつけなかった場合は途中でModelのファイルにタイプヒンティング情報を情報を 上書きしないようにするかをどうか聞かれるので、そのままエンターキーを押せば大丈夫です
