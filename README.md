# Watchmaker

[![CircleCI](https://img.shields.io/circleci/build/github/kozo/watchmaker.svg?style=flat-square)](https://circleci.com/gh/kozo/watchmaker)
[![Scrutinizer code quality (GitHub/Bitbucket)](https://img.shields.io/scrutinizer/quality/g/kozo/watchmaker.svg?style=flat-square)](https://scrutinizer-ci.com/g/kozo/watchmaker/)

- [ ] dragonmantank/cron-expressionを利用してcronの書式をチェックする
- [x] ~~テンプレート作成機能を作る~~
- [ ] オプションを追加する
  - [ ] -b バックアップオプション
  - [ ] -d watchmakerに存在しないものはcronから削除する
  - [ ] -f watchmakerに存在しないものはcronから削除する(-d よりこっちがいいかな？コメント行の管理とかがむずすぎる. forceモードで完全に上書きする)
  - [x] ~~-i 対話モード(非対話モード) quietがデフォルトであるので不要かな？~~
  - [x] ~~--allways カラー強制モード ansiがデフォルトであるので不要かな？~~
- [ ] アンインストールを作る (Cron.phpに設定されてる行を削除する)
- [x] ~~カラーリングを自前実装に切り替える or 別ライブラリを探す (allwasyが使えない, compose scriptで動かすときにカラーリングができるやつ)~~
  - オーバーライドで対応完了
- [ ] watchmaker単体でつかえるようにする
- [ ] リファクタリング
  - [ ] watchmaker
  - [ ] quartz
  - [ ] mechanical
