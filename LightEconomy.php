<?php
class LightEconomy{
    
    private $table;
    private $check;
    private $connection;

    public function __construct($connection,$table = "MoneyTable",$check="uuid")
    {   
        // Setting up the table we will work with
        $this->table = $table;

        // Setting up the method we detect player's account
        $this->check = $check;

        // Creating a connection using my database overlay
        $this->connection = new Database($connection);
    }
    private function selectBalance(MinecraftPlayer $player)
    {
        // Getting all the data about existing account
        return $this->connection->row("SELECT * FROM `{$this->table}` WHERE `{$this->chech}` = '{$player->{$this->chech}}' LIMIT 1");
    }
    private function updateBalance(MinecraftPlayer $player,$value)
    {
        // Updating database record of the player wealth
        return $this->connection->query("UPDATE `{$this->table}` SET `money` = {$value} WHERE `{$this->chech}` = '{$player->{$this->chech}}' LIMIT 1");
    }
    private function createBalance(MinecraftPlayer $player,$value)
    {
        // Inserting new record of the player wealth
        return $this->connection->query("INSERT INTO `{$this->table}` (`uuid`,`name`,`money`,`isPlayer`) VALUES ('{$player->uuid}','{$player->name}','{$value}',1)");
    }
    public function getBalance(MinecraftPlayer $player)
    {
        // Getting all the data about existing account
        $result = $this->selectBalance($player);

        // Returning only the numeric value, or null is doesn't exists
        return (isset($result->money)) ? floatval($result->money) : 0;
    }
    public function topBalance($limit)
    {
        // Filter input parameters
        $limit = intval($limit);

        // Inserting new record of the player wealth
        return $this->connection->rows("SELECT * FROM `{$this->table}` ORDER BY `money` DESC LIMIT {$limit}");
    }
    public function setBalance(MinecraftPlayer $player, $value)
    {
        // Filter input parameters
        $value  = floatval($value);

        // Checking whether is account exists
        $result = $this->selectBalance($player);
        
        if(isset($result->id))
        {
            // Update existing account
            return $this->updateBalance($player,$value);
        }
        else
        {
            // Create new account
            return $this->createBalance($player,$value);
        }
    }
}
?>