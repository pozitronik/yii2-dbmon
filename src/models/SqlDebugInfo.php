<?php
declare(strict_types = 1);

namespace pozitronik\dbmon\models;

use pozitronik\helpers\ArrayHelper;
use Throwable;
use yii\base\Model;
use yii\db\ActiveQuery;

/**
 * Class SqlDebugInfo
 * @property null|string $operation Описание отслеживаемого процесса
 * @property null|int $user_id ID пользователя, вызвавшего процесс
 */
class SqlDebugInfo extends Model {
	public ?string $operation = null;
	public ?string $user_id = null;

	/**
	 * @return string
	 */
	public function __toString() {
		return '/*debug=>'.json_encode([
				'operation' => $this->operation,
				'user_id' => $this->user_id
			]).'<=debug*/';
	}

	/**
	 * extract debug information from sql if exists
	 * @param null|string $sql
	 * @return bool
	 * @throws Throwable
	 */
	public function getFromSql(?string $sql):bool {
		$this->user_id = null;
		$this->operation = null;
		if (null === $sql) return false;
		$matches = [];
		$r = preg_match('/\/\*debug=>(.*?)<=debug\*\//', $sql, $matches);
		if (false !== $r) {
			$this->setAttributes(json_decode(ArrayHelper::getValue($matches, '1', '{}'), true), false);
			return true;
		}

		return false;
	}

	/**
	 * @param ActiveQuery $query
	 * @param string|null $operation
	 * @param int|null $user_id
	 * @return ActiveQuery
	 */
	public static function addDebugInfo(ActiveQuery $query, ?string $operation = null, ?int $user_id = null):ActiveQuery {
		$sqlDebugInfo = new self(compact('operation', 'user_id'));
		return $query->andWhere("1 = 1{$sqlDebugInfo}");
	}

}
