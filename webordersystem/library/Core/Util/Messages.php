<?php

class Core_Util_Messages {
    const W001 = 'W001';
    const W009 = 'W009';
    const W017 = 'W017';
	//login
	const N001 = 'N001 ';
	//chagepass
	const N002 = 'N002 '; //user, token not true
	const N003 = 'N003 ';//qua han changespass
	//sendlink false
	const N004 = 'N004 ';//user not true
	const N005 = 'N005 ';//email not true
	//category null
	const N006 = 'N006 ';
	//mail template
	const N007 = 'N007 ';
	const N008 = 'N008 ';
	
	const W002 = 'W002';
	const W003 = 'W003';
	const W004 = 'W004';
	const W005 = 'W005';
	const W006 = 'W006';
	
	// Font Type
	public static $MESS_ALL = array(
            self::W001 => '{0}を入力してください。',
            self::W009 => '{0}は正しい形式で入力してください。',
            self::W017 => '{0}は無効です。',
			self::N001 => 'ユーザID、パスワードの入力に誤りがあります。',
			self::N002 => 'ユーザID、トークンに誤りがあります。',
			self::N003 => 'パスワード変更の期間が過ぎしました。',
			self::N004 => ' ユーザIDの入力に誤りがあります。',
			self::N005 => ' メールアドレスの入力に誤りがあります。',
			self::N006 => ' 親カテゴリー は必ず入力してください',
			self::N007 => ' タイトル は必ず入力してください。',
			self::N008 => ' 分類 必ず入力してください',
			
			self::W002 => '{0}có chiều dài tối đa là {1}.',
			self::W003 => '{0}không đúng định dạng email.',
			self::W004 => '{0}chỉ bao gồm các kí tự số.',
			self::W005 => '{0}không đúng định dạng ngày (yyyy/mm/dd).',
			self::W006 => '{0}không đúng định dạng (###-####).'
	);
/**
 *
 * @param string $messCode
 * @param array $arrVari
 * @return $message
 */
	public static function getMessage($messCode, $arrVari = array()) {
		if (!is_array($arrVari)) {
			return "";
		} else {
			if (!array_key_exists($messCode, self::$MESS_ALL)) {
				return "";
			}
			$message = self::$MESS_ALL[$messCode];
			$index = 0;
			foreach ($arrVari as $key => $value) {
				$keyVar = '{' . $index . '}';
				$message = str_replace($keyVar, $value, $message);
				$index++;
			}
			return $message;
		}
	}
}
?>