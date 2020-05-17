<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Wpdb_Dumper class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Database Dump By Wpdb
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Wpdb_Dumper extends Lolipop_Migrator_Base_Dumper {
	/**
	 * データベースのダンプを行う
	 *
	 * @throws Lolipop_Migrator_Dump_Exception 処理に失敗した場合
	 */
	public function dump() {
		$class = get_class( $this );

		try {
			$content = sprintf( '%s%s%s', $this->header(), $this->main(), $this->footer() );
		} catch ( Lolipop_Migrator_Dump_Exception $e ) {
			$message = sprintf( '[error] dumper:%s - %s', $class, $e->getMessage() );
			throw new Lolipop_Migrator_Dump_Exception( $message );
		}

		$result = Lolipop_Migrator_File::put_contents( LOLIPOP_MIGRATOR_DUMP_FILE, $content );
		if ( ! $result ) {
			Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_DUMP_FILE );
			$message = sprintf( '[error] dumper:%s - ダンプファイルの出力に失敗した', $class );
			throw new Lolipop_Migrator_Dump_Exception( $message );
		}
	}

	/**
	 * ダンプファイルの開始部分を返却
	 *
	 * @return string
	 */
	protected function header() {
		$header  = "-- Lolipop Migrator\n";
		$header .= "--\n";
		$header .= sprintf( "-- Host: %s    Database: %s\n", DB_HOST, DB_NAME );
		$header .= "-- ------------------------------------------------------\n";
		$header .= sprintf( "-- Server version\t%s\n\n", $this->wpdb->db_version() );
		$header .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
		$header .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
		$header .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
		$header .= "/*!40101 SET NAMES utf8 */;\n";
		$header .= "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n";
		$header .= "/*!40103 SET TIME_ZONE='+00:00' */;\n";
		$header .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n";
		$header .= "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n";
		$header .= "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n";
		$header .= "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n\n";

		return $header;
	}

	/**
	 * ダンプファイルの終了部分を返却
	 *
	 * @return string
	 */
	protected function footer() {
		$footer  = "/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n";
		$footer .= "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n";
		$footer .= "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n";
		$footer .= "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n";
		$footer .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
		$footer .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
		$footer .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";
		$footer .= "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n";
		$footer .= sprintf( "-- Dump completed on %s\n", date( 'Y-m-d H:i:s' ) );

		return $footer;
	}

	/**
	 * ダンプファイルのメイン部分を返却
	 *
	 * @return string
	 * @throws Lolipop_Migrator_Dump_Exception 処理に失敗した場合
	 */
	protected function main() {
		$result = '';

		$privileges = $this->get_privileges();

		$tables     = $this->get_tables();
		$views      = $this->get_views();
		$routines   = $this->get_stored_routines();
		$functions  = isset( $routines['function'] ) ? $routines['function'] : array();
		$procedures = isset( $routines['procedure'] ) ? $routines['procedure'] : array();
		$events     = $this->has_event_privilege( $privileges ) ? $this->get_events() : array();
		$triggers   = $this->has_trigger_privilege( $privileges ) ? $this->get_triggers() : array();

		if ( ! $tables ) {
			throw new Lolipop_Migrator_Dump_Exception( 'テーブルが見つからない' );
		}

		foreach ( $tables as $table ) {
			$result .= $this->table_structure( $table[0] );
			$result .= $this->table_data( $table[0] );
		}

		if ( $views ) {
			foreach ( $views as $view ) {
				$result .= $this->view_structure( $view[0] );
			}
		}

		if ( $functions ) {
			foreach ( $functions as $function ) {
				$result .= $this->function_structure( $function );
			}
		}

		if ( $procedures ) {
			foreach ( $procedures as $procedure ) {
				$result .= $this->procedure_structure( $procedure );
			}
		}

		if ( $events ) {
			foreach ( $events as $event ) {
				$result .= $this->event_structure( $event );
			}
		}

		if ( $triggers ) {
			foreach ( $triggers as $trigger ) {
				$result .= $this->trigger_structure( $trigger );
			}
		}

		return $result;
	}

	/**
	 * テーブルの一覧を返却
	 *
	 * @return array
	 */
	protected function get_tables() {
		$tables = $this->wpdb->get_results( 'SHOW FULL TABLES', ARRAY_N );

		foreach ( $tables as $key => $table ) {
			if ( 'BASE TABLE' !== $table[1] ) {
				unset( $tables[ $key ] );
			}
		}

		return $tables;
	}

	/**
	 * ビューの一覧を返却
	 *
	 * @return array
	 */
	protected function get_views() {
		$views = $this->wpdb->get_results( 'SHOW FULL TABLES', ARRAY_N );

		foreach ( $views as $key => $view ) {
			if ( 'VIEW' !== $view[1] ) {
				unset( $views[ $key ] );
			}
		}

		return $views;
	}

	/**
	 * ストアドルーチン(プロシージャおよび関数の両方)の一覧を返却
	 *
	 * @return array
	 */
	protected function get_stored_routines() {
		$routines = $this->wpdb->get_results( 'SELECT * FROM information_schema.ROUTINES', ARRAY_A );
		$result   = array();

		foreach ( $routines as $routine ) {
			if ( 'PROCEDURE' === $routine['ROUTINE_TYPE'] ) {
				$result['procedure'][] = $routine['SPECIFIC_NAME'];
			} elseif ( 'FUNCTION' === $routine['ROUTINE_TYPE'] ) {
				$result['function'][] = $routine['SPECIFIC_NAME'];
			}
		}

		return $result;
	}

	/**
	 * EVENT の一覧を返却
	 *
	 * @return array
	 */
	protected function get_events() {
		$events = $this->wpdb->get_results( 'SHOW EVENTS', ARRAY_A );
		$result = array();

		foreach ( $events as $event ) {
			$result[] = $event['Name'];
		}

		return $result;
	}

	/**
	 * トリガーの一覧を返却
	 *
	 * @return array
	 */
	protected function get_triggers() {
		$triggers = $this->wpdb->get_results( 'SHOW TRIGGERS', ARRAY_A );
		$result   = array();

		foreach ( $triggers as $trigger ) {
			$result[] = $trigger['Trigger'];
		}

		return $result;
	}

	/**
	 * 指定されたテーブルに対する CREATE TABLE 文を生成して返却
	 *
	 * @param  string $table テーブル名
	 * @return string 生成した CREATE TABLE 文
	 * @throws Lolipop_Migrator_Dump_Exception CREATE TABLE 文の生成に失敗した場合
	 */
	protected function table_structure( $table ) {
		$create_table_query = $this->wpdb->get_row( sprintf( 'SHOW CREATE TABLE `%s`', $table ), ARRAY_N );

		if ( ! $create_table_query ) {
			$message = sprintf( 'CREATE TABLE 文の生成に失敗した[table: %s]', $table );
			throw new Lolipop_Migrator_Dump_Exception( $message );
		}

		$result  = "--\n";
		$result .= "-- Table structure for table `$table`\n";
		$result .= "--\n\n";
		$result .= "DROP TABLE IF EXISTS `$table`;\n";
		$result .= "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n";
		$result .= "/*!40101 SET character_set_client = utf8 */;\n";
		$result .= $create_table_query[1] . ";\n";
		$result .= "/*!40101 SET character_set_client = @saved_cs_client */;\n\n";

		return $result;
	}

	/**
	 * 指定されたテーブルに対するダンプデータを生成して返却
	 *
	 * @param  string $table テーブル名
	 * @return string 指定されたテーブルに対するダンプデータ
	 * @throws Lolipop_Migrator_Dump_Exception レコード数が食い違っている場合
	 */
	protected function table_data( $table ) {
		$selected_data = $this->get_selected_data( $table );

		$result  = "--\n";
		$result .= "-- Dumping data for table `$table`\n";
		$result .= "--\n\n";
		$result .= "LOCK TABLES `$table` WRITE;\n";
		$result .= "/*!40000 ALTER TABLE `$table` DISABLE KEYS */;\n";

		if ( count( $selected_data ) > 0 ) {
			foreach ( $selected_data as $key => $data ) {
				$head     = ( 0 === $key % 100 || 0 === $key ) ? sprintf( 'INSERT INTO `%s` VALUES ', $table ) : '';
				$trailing = ( 0 === ( $key + 1 ) % 100 || end( $selected_data ) === $data ) ? ';' : ',';
				$result  .= $this->insert_into( $head, array_values( $data ), $trailing );
			}

			$result .= "\n";
		}

		$result .= "/*!40000 ALTER TABLE `$table` ENABLE KEYS */;\n";
		$result .= "UNLOCK TABLES;\n\n";

		return $result;
	}

	/**
	 * 指定されたテーブルに対する情報を返却
	 *
	 * @param  string $table     テーブル名
	 * @return array|null|object 指定されたテーブルに対する情報
	 * @throws Lolipop_Migrator_Dump_Exception レコード数が食い違っている場合
	 */
	protected function get_selected_data( $table ) {
		$primary_keys = array(
			$this->wpdb->prefix . 'commentmeta'   => 'meta_ID',
			$this->wpdb->prefix . 'comments'      => 'comment_ID',
			$this->wpdb->prefix . 'links'         => 'link_id',
			$this->wpdb->prefix . 'options'       => 'option_id',
			$this->wpdb->prefix . 'postmeta'      => 'meta_id',
			$this->wpdb->prefix . 'posts'         => 'ID',
			$this->wpdb->prefix . 'termmeta'      => 'meta_id',
			$this->wpdb->prefix . 'terms'         => 'term_id',
			$this->wpdb->prefix . 'term_taxonomy' => 'term_taxonomy_id',
			$this->wpdb->prefix . 'usermeta'      => 'umeta_id',
			$this->wpdb->prefix . 'users'         => 'ID',
		);

		$limit  = 1000;
		$offset = 0;

		$count = intval( $this->wpdb->get_var( sprintf( 'SELECT COUNT(*) FROM `%s`', $table ) ) );

		if ( 0 === $count ) {
			return $this->wpdb->get_results( sprintf( 'SELECT * FROM `%s`', $table ), ARRAY_A );
		}

		$max_id     = ( isset( $primary_keys[ $table ] ) ) ? intval( $this->wpdb->get_var( sprintf( 'SELECT MAX(`%s`) FROM `%s`', $primary_keys[ $table ], $table ) ) ) : 0;
		$loop_count = ( 0 !== $max_id ) ? intval( ceil( $max_id / $limit ) ) : intval( ceil( $count / $limit ) );

		$result = array();

		if ( isset( $primary_keys[ $table ] ) ) {
			$to          = 0;
			$primary_col = $primary_keys[ $table ];

			for ( $i = 0; $i < $loop_count; $i++ ) {
				$from = $to + 1;
				$to   = ( $i + 1 ) * 1000;
				$rows = $this->wpdb->get_results( sprintf( 'SELECT * FROM `%s` WHERE %s BETWEEN %s AND %s', $table, $primary_col, $from, $to ), ARRAY_A );

				foreach ( $rows as $row ) {
					$result[] = $row;
				}
			}
		} else {
			for ( $i = 0; $i < $loop_count; $i++ ) {
				$rows = $this->wpdb->get_results( sprintf( 'SELECT * FROM `%s` LIMIT %s OFFSET %s', $table, $limit, $offset ), ARRAY_A );
				foreach ( $rows as $row ) {
					$result[] = $row;
				}
				$offset = $limit + $offset;
			}
		}

		$execution_count = count( $result );

		if ( $count !== $execution_count ) {
			$message = sprintf( '処理したレコード数が実際のレコード数とあっていない[table: %s][実際の値: %s][処理した値: %s]', $table, $count, $execution_count );
			throw new Lolipop_Migrator_Dump_Exception( $message );
		}

		return $result;
	}

	/**
	 * データ挿入部を生成して返却
	 *
	 * @param  string $head          INSERT INTO ... VALUES
	 * @param  array  $selected_data 挿入するデータ
	 * @param  string $trailing      連結文字
	 * @return string
	 */
	protected function insert_into( $head, $selected_data, $trailing ) {
		$result = "$head(";
		foreach ( array_values( $selected_data ) as $data ) {
			$result .= is_null( $data ) ? 'NULL,' : sprintf( "'%s',", $this->add_slashes_to_string( $data ) );
		}
		return rtrim( $result, ',' ) . ")$trailing";
	}

	/**
	 * ビューの構造を生成して返却
	 *
	 * @param  string $view ビュー名
	 * @return string
	 */
	protected function view_structure( $view ) {
		$create_view_query = $this->wpdb->get_row( sprintf( 'SHOW CREATE VIEW `%s`', $view ), ARRAY_N );
		$replaced_query    = preg_replace( '/CREATE\s.*\sVIEW/', 'CREATE VIEW', $create_view_query[1] );

		if ( $create_view_query[1] === $replaced_query || is_null( $replaced_query ) ) {
			return '';
		}

		$result  = "--\n";
		$result .= "-- View structure for view `$view`\n";
		$result .= "--\n\n";
		$result .= "DROP VIEW IF EXISTS `$view`;\n";
		$result .= $replaced_query . ";\n\n";

		return $result;
	}

	/**
	 * 関数の構造を生成して返却
	 *
	 * @param  string $function 関数名
	 * @return string
	 */
	protected function function_structure( $function ) {
		$create_function_query = $this->wpdb->get_row( sprintf( 'SHOW CREATE FUNCTION `%s`', $function ), ARRAY_A );
		$replaced_query        = preg_replace( '/CREATE\s.*\sFUNCTION/', 'CREATE FUNCTION', $create_function_query['Create Function'] );

		if ( $create_function_query['Create Function'] === $replaced_query || is_null( $replaced_query ) ) {
			return '';
		}

		$result  = "DROP FUNCTION IF EXISTS `$function`;\n";
		$result .= "DELIMITER ;;\n";
		$result .= "$replaced_query;;\n";
		$result .= "DELIMITER ;\n\n";

		return $function_structure;
	}

	/**
	 * プロシージャの構造を生成して返却
	 *
	 * @param  string $procedure プロシージャ名
	 * @return string
	 */
	protected function procedure_structure( $procedure ) {
		$create_procedure_query = $this->wpdb->get_row( sprintf( 'SHOW CREATE PROCEDURE `%s`', $procedure ), ARRAY_A );
		$replaced_query         = preg_replace( '/CREATE\s.*\sPROCEDURE/', 'CREATE PROCEDURE', $create_procedure_query['Create Procedure'] );

		if ( $create_procedure_query['Create Procedure'] === $replaced_query || is_null( $replaced_query ) ) {
			return '';
		}

		$result  = "DROP PROCEDURE IF EXISTS `$procedure`;\n";
		$result .= "DELIMITER ;;\n";
		$result .= "$replaced_query;;\n";
		$result .= "DELIMITER ;\n\n";

		return $result;
	}

	/**
	 * EVENT の構造を生成して返却
	 *
	 * @param  string $event イベント名
	 * @return string
	 */
	protected function event_structure( $event ) {
		$create_event_query = $this->wpdb->get_row( sprintf( 'SHOW CREATE EVENT `%s`', $event ), ARRAY_A );
		$replaced_query     = preg_replace( '/CREATE\s.*\sEVENT/', 'CREATE EVENT', $create_event_query['Create Event'] );

		if ( $create_event_query['Create Event'] === $replaced_query || is_null( $replaced_query ) ) {
			return '';
		}

		$result  = "DROP EVENT IF EXISTS `$event`;\n";
		$result .= "DELIMITER ;;\n";
		$result .= "$replaced_query ;;\n";
		$result .= "DELIMITER ;\n\n";

		return $result;
	}

	/**
	 * トリガーの構造を生成して返却
	 *
	 * @param  string $trigger トリガー名
	 * @return string
	 */
	protected function trigger_structure( $trigger ) {
		$create_trigger_query = $this->wpdb->get_row( sprintf( 'SHOW CREATE TRIGGER %s', $trigger ), ARRAY_A );
		$replaced_query       = preg_replace( '/CREATE\s.*\sTRIGGER/', 'CREATE TRIGGER', $create_trigger_query['SQL Original Statement'] );

		if ( $create_trigger_query['SQL Original Statement'] === $replaced_query || is_null( $replaced_query ) ) {
			return '';
		}

		$result  = "DROP TRIGGER IF EXISTS `$trigger`;\n";
		$result .= "DELIMITER ;;\n";
		$result .= "$replaced_query ;;\n";
		$result .= "DELIMITER ;\n\n";

		return $result;
	}

	/**
	 * スラッシュをエスケープする
	 *
	 * @param  string $str エスケープする文字列
	 * @return mixed
	 */
	protected function add_slashes_to_string( $str = '' ) {
		return str_replace(
			array( '\\', "\0", "\n", "\r", "'", '"', "\xla" ),
			array( '\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\z' ),
			$str
		);
	}
}
