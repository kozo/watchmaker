# watchmaker

- [ ] dragonmantank/cron-expressionを利用してcronの書式をチェックする
- [ ] オプションを追加する
  - [ ] -b バックアップオプション
  - [ ] -d watchmakerに存在しないものはcronから削除する
  - [ ] -i 対話モード(非対話モード)
  - [ ] --allways カラー強制モード
- [ ] カラーリングを自前実装に切り替える or 別ライブラリを探す (allwasyが使えない, compose scriptで動かすときにカラーリングができるやつ)
- [ ] リファクタリング
  - [ ] watchmaker
  - [ ] quartz
  - [ ] mechanical
