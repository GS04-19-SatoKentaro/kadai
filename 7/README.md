#アプリ概要
アンケート集計。
課題6のものをベースに、データベースを利用する動作に変更。

また、アンケート結果を見るログイン機能等を追加。

##デモサイト
1. https://gs-4-19-satokentaro-kadai-7.appspot.com/index.php
2. https://gs-4-19-satokentaro-kadai-7.appspot.com/insert.php
3. https://gs-4-19-satokentaro-kadai-7.appspot.com/select.php
4. https://gs-4-19-satokentaro-kadai-7.appspot.com/login.php
5. https://gs-4-19-satokentaro-kadai-7.appspot.com/logout.php
6. https://gs-4-19-satokentaro-kadai-7.appspot.com/register.php

###制限事項
GoogleAppEngineを利用。デモサイトのものは、データベースへの接続まわりのコードはGoogle Cloud SQLに合わせて多少記述を変更。

##主な機能

###index.php（課題6のinput_data.phpベースに改変）
1. アンケート入力機能。「insert.php」に入力値を渡してデータベース「an」の「an_table」テーブルに書き込み。
2. 必須項目以外は任意。
3. 必須項目が全て入力されると、送信ボタンがアクティブになる。
4. 入力項目が変更されるたびにSessionStorageに保存。リロード等で再反映。
5. 郵便番号を入力すると住所を保管。
6. 5段階評価は数値入力と、星マークのクリックのいずれかで入力。
7. 入力内容を消去ボタンで、フォーム内の入力内容とSessionStorageの内容を消去。
8. 右上の「Login」ボタンで「select.php」に移動。

###select.php（課題6のoutput_data.phpベースに改変）
####ログイン機能
1. 「login.php」を読み込み、ログインフォームを表示。
2. メールアドレスとパスワードがデータベース「an」の「users」テーブルにあれば、ログインし、アンケート結果を表示。

####ユーザー登録機能
1. ログイン画面の右上の「Register」ボタンで「register.php」に移動。名前、メールアドレス、パスワードでデータベース「an」の「users」テーブルにユーザー登録。

####アンケート結果の表示機能。
1. アンケート結果の一覧・アンケート結果（個別）のタブで表示を切り替え。

#####アンケート結果の一覧
1. データベース「an」の「an_table」テーブルの内容を読み込んで表形式で表示。
2. 表はヘッダーを固定で中身をスクロール。

#####アンケート結果（個別）
1. アンケート結果の評価の平均値を数値と星で表示。
2. 評価の票数と、それぞれの星評価のパーセントを数値とグラフで表示。
3. アンケート結果の男女比を数とパーセントのグラフで表示。（Google Chartsの機能を利用）

####ログアウト機能
1. 右上の「Logout」ボタンで「logout.php」に移動、即ログアウト。

###今後の課題等
1. 前回、今回の課題において、とりあえずで実装してきたが、データベースの管理や、画面遷移、セキュリティー対策など、設計方針をなるべく明確にし、カオスにならないようにしたい。
2. Google Chartsのカスタマイズもすんなりとはいかず、仕様の適切な理解と実装が必要だと思いました。