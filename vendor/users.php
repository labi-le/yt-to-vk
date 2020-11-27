 <?php

    class User
    {

        private const TAB = 'users';
        private const second_column = 'user_id = ?';

        public static function get($id)
        {
            return R::findOne(self::TAB, self::second_column, [$id]) ?? false;
        }

        private static function getter($id, $where)
        {
            return R::findOne(self::TAB, self::second_column, [$id])->$where ?? false;
        }

        protected static function setter($id, $where, $what)
        {
            $chat = self::get($id);
            $chat->$where = $what;

            R::store($chat);
        }

        public static function status_menu($id)
        {
            return boolval(self::getter($id, 'no_preview'));
        }

        public static function switch_preview($id)
        {
            $status = boolval(self::getter($id, 'no_preview'));
            self::setter($id, 'no_preview', !$status);
        }

        public static function create($id)
        {
            $user = R::Dispense(self::TAB);

            $user->user_id = $id;
            $user->no_menu = false;
            $user->token = false;

            R::Store($user);
        }


        public static function setToken($id, $token)
        {
            self::setter($id, 'token', $token);
        }

        public static function getToken($id)
        {
            return self::getter($id, 'token');
        }
    }