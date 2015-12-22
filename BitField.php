<?php
  // Class BitField
  class BitField
  {
    protected $roles = array();
    protected $permission;
    public function __construct()
    {
      $this->setPermission(0);
    }
    public function check($action)
    {
      if (array_key_exists($action, $this->roles))
      {
        return (($this->permission & $this->roles[$action]) == $this->roles[$action]);
      }
      return FALSE;
    }
    public function add($action)
    {
      if (array_key_exists($action, $this->roles))
      {
        $this->permission |= $this->roles[$action];
        return TRUE;
      }
      return FALSE;
    }
    public function remove($action)
    {
      if (array_key_exists($action, $this->roles))
      {
        $this->permission &= ~$this->roles[$action];
        return TRUE;
      }
      return FALSE;
    }
    public function setPermission($permission)
    {
      $this->permission = $permission;
    }
    public function getPermission()
    {
      return $this->permission;
    }
    public function getAllRoles()
    {
      return $this->roles;
    }
  }
  // Test
  class Permission extends BitField
  {
    protected $roles = array(
        'ACTION1' => 1,
        'ACTION2' => 2,
        'ACTION3' => 4,
        'ACTION4' => 8,
        'ACTION5' => 16
      );
  }
  $a = new Permission;
  $a->setPermission(18);
  echo '<ul>';
  for ($i = 1; $i <= 5; $i++)
  {
    echo '<li>ACTION' . $i . ' : ';
    if ($a->check('ACTION' . $i))
    {
      echo 'OK :D';
    }
    else
    {
      echo 'NO :(';
    }
    echo '</li>';
  }
  echo '</ul>';
