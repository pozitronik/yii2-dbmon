<?php
declare(strict_types = 1);

namespace pozitronik\dbmon\models;

use yii\base\Model;

/**
 * Class ProcessListItem
 * Элемент списка процессов + информация отладки
 * @property null|int $id
 * @property null|string $db
 * @property null|string $command
 * @property null|string $time
 * @property null|string $state
 * @property null|string $query
 * @property null|int $user_id
 * @property null|string $operation
 */
class ProcessListItem extends Model {
	public ?string $id = null;
	public ?string $db = null;
	public ?string $command = null;
	public ?string $time = null;
	public ?string $state = null;
	public ?string $query = null;
	public ?string $user_id = null;
	public ?string $operation = null;
}