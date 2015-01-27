<?php

/**
 * Maintenance script used for getting revisions with tags (for example Editor type used for generating revision)
 *
 * Env vars:
 * [int] SERVER_ID - wiki id | example 12345
 * [string] START_DATE - format D-M-Y example "01-01-2015"
 * [string] END_DATE - format D-M-Y example "01-01-2015"
 * [string] STATS_DB_HOST - stats db host name
 * [string] STATS_DB_NAME - stats db name
 * [string] STATS_DB_USER - stats db user
 * [string] STATS_DB_PASS - stats db password
 */

require_once( dirname( __FILE__ ) . '../../Maintenance.php' );

class GetRevisionWithTags extends Maintenance {
	private static $editorTags = [
		'rte-source',
		'rte-wysiwyg',
		'visualeditor',
		'mobileedit'
	];

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Get Editor Stats';
	}

	public function execute() {
		$data = $this->getRevisionsData();

		if (!empty($data)) {
			$this->insertStatsToDb($data);
		}
	}

	private function getRevisionsData() {
		$query = $this->createrevisionsQuery();

		return $query->run(wfGetDB(DB_SLAVE), function (ResultWrapper $result) {
			$data = [];
			while ($row = $result->fetchObject()) {
				$data[] = $this->createRevisionEntry($row);
			}

			return $data;
		});
	}

	private function createrevisionsQuery() {
		$timeStampStart = date('YMDHIs', strtotime($_SERVER['START_DATE']));
		$timeStampEnd = date('YMDHIs', strtotime($_SERVER['END_DATE']));

		return (new WikiaSQL())
			->SELECT()
			->FIELD('rev_id')
			->FROM('revision')
			->LEFT_JOIN('tag_summary')
			->ON('ts_rev_id', 'rev_id')
			->FIELD('ts_tags')
			->WHERE('rev_timestamp')
			->BETWEEN($timeStampStart, $timeStampEnd);
	}

	private function createRevisionEntry($row) {
		return [
			'wiki_id' => $_SERVER['SERVER_ID'],
			'revision_id' => $row->rev_id,
			'editor' => $this->sanitizeRevisionTag($row->ts_tags)
		];
	}

	private function sanitizeRevisionTag($tagBlob) {
		foreach (self::$editorTags as $tag) {
			if (strpos($tagBlob, $tag) !== false) {
				return $tag;
			}
		}

		return null;
	}

	private function insertStatsToDb($data) {
		$statsDbHostName = $_SERVER['STATS_DB_HOST'];
		$statsDbName = $_SERVER['STATS_DB_NAME'];
		$statsDbUser = $_SERVER['STATS_DB_USER'];
		$statsDbPass = $_SERVER['STATS_DB_PASS'];

		$dbh = null;

		try {
			$dbh = new PDO("mysql:host=$statsDbHostName;dbname=$statsDbName", $statsDbUser, $statsDbPass);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$dbh->beginTransaction();

			$stmt = $dbh->prepare("INSERT IGNORE INTO editorstats (wiki_id, revision_id, editor)
 				VALUES (:wiki_id, :revision_id, :editor)");
			$stmt->bindParam(':wiki_id', $wiki_id);
			$stmt->bindParam(':revision_id', $revision_id);
			$stmt->bindParam(':editor', $editor);

			foreach ($data as $rev) {
				$wiki_id = $rev['wiki_id'];
				$revision_id = $rev['revision_id'];
				$editor = $rev['editor'];

				$stmt->execute();
			}

			$dbh->commit();
		} catch(PDOException $error) {
			$dbh->rollback();
			print($error->getMessage());
		}
	}
}

$maintClass = 'GetRevisionWithTags';
require_once( RUN_MAINTENANCE_IF_MAIN );
