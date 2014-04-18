<?
 
  $username = $_POST['username'];
  $query = $this->EE->db->query("SELECT salt FROM exp_members WHERE username = '$username' AND salt != '' LIMIT 1");
  if ($query->num_rows() > 0)
  {
    $salt = $query->row('salt');
    $password = hash('sha512', $salt.$_POST['password']);
  }
  else
  {
    $this->EE->load->library('encrypt');
    $password = $this->EE->encrypt->sha1($_POST['password']);
  }
  $query = $this->EE->db->query("SELECT member_id, email, username FROM exp_members WHERE username = '$username' AND password = '$password' LIMIT 1");
  if ($query->num_rows() > 0)
  {
    $this->EE->session->create_new_session($query->row('member_id'), TRUE);
    $this->EE->session->userdata['username']  = $query->row('username');
    $this->EE->session->userdata['email'] = $query->row('email');
    $this->EE->session->userdata['group_id'] = 1;
    if ($this->EE->extensions->end_script === TRUE) return;
    echo json_encode(array('success', $this->EE->session->userdata['session_id']));
  } 
  else
  {
    echo json_encode(array('Your username or password is incorrect', ''));
  }
 
?>