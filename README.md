Bit Field Permission
====================

### Giới thiệu

![Ky thuat BitField trong phan quyen](https://dl.dropboxusercontent.com/u/120005387/songvidamme/2013/Jul/bitfield1.png)

Đối với 1 website hay software có thành viên ( users ) thì việc phân chia thành từng nhóm ( groups ) để có thể dễ dàng phân quyền với hàng tá chức năng của website, software là một vấn đề thường gặp & khá nan giải. Kĩ thuật BitField được sử dụng khá phổ biến & linh hoạt. Qua bài viết này, bạn sẽ thấy được sự 'ảo diệu' của hệ nhị phân ( 0 và 1 )... 

### Phân tích

#### Tại sao lại có nhị phân trong này ???
 - Hệ nhị phân ( nôm na là hệ có mỗi 2 con số 0 & 1 ) là nền tảng cơ sở cho CNTT. Mọi thứ có 2 trạng thái mâu thuẫn nhau ( có & không ; mở & tắt ; được & mất ; v.v.. ) đều có thể biểu diễn bằng hệ nhị phân.
 - Vấn đề chính của chúng ta là phân chia được quyền hạn của 1 thành viên bất kì. => Nhận xét kĩ ta thấy rằng quyền hạn có thể biểu diễn bằng hệ nhị phân ( được phép & không được phép ).

#### Vậy dùng nhị phân để phân quyền như thế nào ???
 - Ta quy ước thế này :
  + Có 2 trạng thái : được phép là 1 ; không được phép là 0.
  + Mỗi quyền hạn ( quyền đăng bài, xóa bài, sửa bài, comment, like, quyền ... vân vân ) sẽ có 1 trong 2 trạng thái như trên.
  + Sắp xếp theo 1 trình tự 'cố định do ta quy định' dãy số đó => Ta có 1 dãy toàn số 0,1 => Dãy Bit ;)
  + OK, bây giờ có phải từ 1 dãy Bit đó ta có thể suy ra 1 số thập phân đặc trưng cho dãy không ? ;)
 - Ví dụ minh họa :

![Ky thuat BitField trong phan quyen](https://dl.dropboxusercontent.com/u/120005387/songvidamme/2013/Jul/bitfield2.png)

=> **18 là giá trị 'duy nhất' biểu diễn quyền cho 5 actions ( action 2,5 được phép ; còn lại không được phép ). Ta chỉ cần lưu số 18 vào CSDL ( tiết kiệm dung lượng cho CSDL :D ). Từ con số 18 ta cũng có thể suy ngược lại dãy bit => lấy được quyền hạn của user đó :D**

### Áp dụng vào thực tiễn

Tôi sẽ mô phỏng lại những gì ta có được bên trên thành 1 class PHP ( ngôn ngữ khác cũng tương tự )

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
					return (($this->permission & $this->roles[$action]) > 0);
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

#### Kết quả test

![Ky thuat BitField trong phan quyen](https://dl.dropboxusercontent.com/u/120005387/songvidamme/2013/Jul/bitfield3.png)

### Rút kết

**Cái mà tôi muốn nhấn mạnh là không phải là phân quyền dùng BitField ra sao, mà cái chính là để giới thiệu sức mạnh của 2 con số 0 và 1. ;) Hãy nhớ kĩ : Những gì có 2 trạng thái thì thường có thể dùng nhị phân để giải quyết :D**
