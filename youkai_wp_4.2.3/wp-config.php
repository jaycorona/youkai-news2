<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、MySQL、テーブル接頭辞、秘密鍵、ABSPATH の設定を含みます。
 * より詳しい情報は {@link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86 
 * wp-config.php の編集} を参照してください。MySQL の設定情報はホスティング先より入手できます。
 *
 * このファイルはインストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さず、このファイルを "wp-config.php" という名前でコピーして直接編集し値を
 * 入力してもかまいません。
 *
 * @package WordPress
 */

// 注意: 
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'youkai-news2');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'root');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', '');

/** MySQL のホスト名 */
define('DB_HOST', 'localhost');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8mb4');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Zu#~3}[oWPU-bDLc4>H2xOT^m9{8W0yIu^Kc>1MaKy79J8;v3M-;)IfuqvCTtG:,');
define('SECURE_AUTH_KEY',  '&p:OSHP%>P-xmnIz?0]|e-|Ja[53ASQb8AzL}r*:tnA%+W|cRK^E94c7+$}T[Vov');
define('LOGGED_IN_KEY',    'n+;B-hW-br_xR>)sC6kLN9`OQc4ua{Y|fn]21uNfA/9rz-4tN-Hc|j.(E]9Vi47n');
define('NONCE_KEY',        'doE5GHa>?HJu=2c w:1uNb5Id7=F&(9y6BdlSr;@-c`CC6%.kPrKKON5wu>I|h8#');
define('AUTH_SALT',        'tB7a~MTEgbjA)LH-jc;*J-Y ywyIXBIOs.Wv2c:=fym?2eF[-OE1]4=doa+,?;/B');
define('SECURE_AUTH_SALT', '|tM{tta&Az(L b<Wk[tgV5oDCIql` `ic<#1F9LJ/- x-h_-A3O2C;n,Eaa-<JeB');
define('LOGGED_IN_SALT',   'oY/[}Xc#k7{4B;FFv0V6R=4`.~}.7{)n{-9eZH(gC:e:Aze_VSj%Q=u$~0w`tBij');
define('NONCE_SALT',       'AxBPL++XfJfm2sOqJU x{>qW9e601aBrlYwP6b(km=^L@T|kP=,c+JOyL%&5 X^<');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wpyou_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
