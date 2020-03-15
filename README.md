# watchmaker

- [ ] dragonmantank/cron-expressionを利用してcronの書式をチェックする
- [ ] オプションを追加する
  - [ ] -b バックアップオプション
  - [ ] -d watchmakerに存在しないものはcronから削除する
  - [ ] -i 対話モード(非対話モード) quietがデフォルトであるので不要かな？
  - [ ] --allways カラー強制モード ansiがデフォルトであるので不要かな？
- [ ] カラーリングを自前実装に切り替える or 別ライブラリを探す (allwasyが使えない, compose scriptで動かすときにカラーリングができるやつ)
- [ ] リファクタリング
  - [ ] watchmaker
  - [ ] quartz
  - [ ] mechanical
