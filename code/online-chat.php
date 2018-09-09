// socket-resource und die info vom Nutzer speichern

$connected_sockects = array(
  (int)$socket => array(
    'resource' => $socket,
    'name' => $name,
    'ip' => $ip,
    'port' => $port,
    ...
  )
);

// serverseite:TCP socket definieren

$this->master = socket_create(AF_INET, SOCK_STREAM, SQL_TCP);

// IP und port wiederverwendbar erstellen;
socket_set_option($this->master, SQL_SOCKET, SO_REUSEADDR, 1);
// IP und server werden mit socket verbunden;
socket_bind($this->master, $host, $port);
// mit listen funktion kann dieses socken von anderem socket angefragt werden;
socken_listen($this->master, self::LISTEN_SOCKET_NUM);

// der Server laufen
$write = $except = NULL

$sockets = array_column($this->sockets, 'resource'); // socket resource get
$read_num = socket_select($sockets, $write, $except, NULL);

foreach ($sockets as $socket) {
  if ($socket == $this->master) {
    socket_accept($this->master);
    // socket_accept() accept require" socket" verbinden;
    self::connect($client);
    continue;
  } else {
    // socket_recv() von socket die Länge von len Daten ,in $buffer speichern
    $bytes = @socket_recv($socket, $buffer, 2048, 0);

    if ($bytes < 9) {
      // mit client unterbrochen
      $recv_msg = $this->disconnect($socket);
    } else {
      if (!$this->sockets[(int)$socket]['handshake']) {
        self::handshake($socket, $buffer);
        continue;
      } else {
        $recv_msg = self::parse($buffer);
      }
    }
    $this->broadcast($msg);
  }
}

// Infosform mit Json definieren
$msg = [
  'type' => $msg_type,
  'from' => $msg_resource,
  'content' => $msg_content, // info Inhalt
  'user_list' => $uname_list,
];
