#アプリ概要
アンケート集計。

##デモサイト
1. https://gs-4-19-satokentaro-kadai-6.appspot.com/input_data.php
2. https://gs-4-19-satokentaro-kadai-6.appspot.com/fputs.php
3. https://gs-4-19-satokentaro-kadai-6.appspot.com/output_data.php

###制限事項
GoogleAppEngineを利用しているが、ファイル書き込みができないようなので、fputs.phpからの「data.csv」への書き込み動作は動作しない。

##主な機能

###input_data.php
1. アンケート入力機能。「fputs.php」に入力値を渡して「data.csv」に書き込み。
2. 必須項目以外は任意。
3. 必須項目が全て入力されると、送信ボタンがアクティブになる。
4. 入力項目が変更されるたびにSessionStorageに保存。リロード等で再反映。
5. 郵便番号を入力すると住所を保管。
6. 5段階評価は数値入力と、星マークのクリックのいずれかで入力。
7. 入力内容を消去ボタンで、フォーム内の入力内容とSessionStorageの内容を消去。

###output_data.php
1. アンケート結果の表示機能。
2. data/data.csvの内容を読み込んで表形式で表示。
3. 表はヘッダーを固定で中身をスクロール。
4. アンケート結果の評価の平均値を数値と星で表示。
5. 評価の票数と、それぞれの星評価のパーセントを数値とグラフで表示。

###その他
1. 評価以外の項目もグラフ等の表示をしたかったが、手が回りませんでした。