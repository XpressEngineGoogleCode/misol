<?
	// last update : 2005.09.19.002

	class http
	{
		var $debug = 0;

		var $socket;
		var $host;
		var $port;
		var $timeout;
		var $path;
		var $method;
		var $send_header;
		var $send_parameter;
		var $receive;
		var $cookie;

		function http($host, $port=80, $timeout=30)
		{
			$this->host = $host;
			$this->port = $port;
			$this->timeout = $timeout;
			$this->open($host, $port, $timeout);

			$this->send_header["Accept"] = "*/*";
			$this->send_header["Content-Type"] = "application/x-www-form-urlencoded";
			$this->send_header["User-Agent"] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)";
			$this->send_header["Connection"] = "Keep-Alive";
			$this->send_header["Cache-Control"] = "no-cache";
			$this->send_header["Referer"] = "http://$host";
			$this->send_header["Host"] = $host;
		}

		function getBody()
		{
			return $this->receive["Body"];
		}

		function open($host, $port=80, $timeout=30)
		{
			$this->host = $host;
			$this->port = $port;
			$this->timeout = $timeout;

			$this->send_header["Referer"] = "http://$host";
			$this->send_header["Host"] = $host;

			return $this->socket = fsockopen($host, $port, $errno, $errstr, $timeout);
		}

		function close()
		{
			@fclose($this->socket);
			unset($this->socket);
		}

		function setTarget($path)
		{
			$this->path = $path;

			$this->method = "GET";
			$this->send_header["Host"] = $this->host;
			unset($this->send_header["Content-Length"]);
		}

		function setHeader($name, $value)
		{
			$this->send_header[$name] = $value;
		}

		function sendData()
		{
			while( true )
			{
				if( $this->socket )
				{
					$data = "$this->method $this->path HTTP/1.1\r\n";

					foreach($this->send_header as $header => $value)
						$data .= "$header: $value\r\n";

					$data .= $this->getCookie() . "\r\n";

					if( $this->method == "POST" )
						$data .= $this->send_parameter;

					fwrite($this->socket, $data);

					if( $this->debug )
						echo "<div style='text-align: justify; font-size: 9pt; font-family: ±¼¸²; line-height: 150%; word-break: break-all; color: red'>".str_replace("\r\n", "<br>", $data)."</div>";

					$this->readData();

					break;
				}
				else if (!$this->open($this->host, $this->port, $errno, $errstr, $this->timeout))
					break;
			}
		}

		function readData()
		{
			unset($data);
			unset($this->receive);

			$this->receive["Status"] = "100";

			while( $this->receive["Status"] == "100" )
			{
				while( true )
				{
					$data = fgets($this->socket, 1024);

					if(strlen($data)==0)
						return;

					if( $data == "\r\n" )
						break;
					else if(substr($data, 0, 5) == "HTTP/")
						$this->receive["Status"] = substr($data, 9, 3);
					else
					{
						$pos = strpos($data, ":");

						$name = substr($data, 0, $pos);
						$value = substr($data, $pos+2);

						if(strtoupper($name) == "SET-COOKIE")
							$this->setCookie($value);
						else
							$this->receive[$name] = str_replace("\r\n", "", $value);
					}
				}
			}

			if(	$this->receive["Transfer-Encoding"] == "chunked" )
			{
				while( true )
				{
					unset($buff);

					$length = hexdec(fgets($this->socket, 1024));

					if( $length == 0 )
						break;

					while( strlen($buff) < $length+2 )
						$buff .= fread($this->socket, $length+2-strlen($buff));

					$this->receive["Body"] .= substr($buff, 0, strlen($buff)-2);
				}
			}
			else if( $this->receive["Content-Length"] )
			{
				$this->receive["Body"] = fread($this->socket, $this->receive["Content-Length"]);

				while( strlen($this->receive["Body"]) < $this->receive["Content-Length"] )
					$this->receive["Body"] .= fread($this->socket, $this->receive["Content-Length"]-strlen($this->receive["Body"]));
			}

			if( $this->receive["Connection"] == "close" )
			{
				while( $buff = fgets($this->socket, 8192) )
					$this->receive["Body"] .= $buff;

				$this->close();
				$this->open($this->host, $this->port, $this->timeout);
			}

			if( $this->debug )
				$this->dumpAll();
		}

		function setCookie($value)
		{
			$buff = split("\r\n", $value);

			foreach($buff as $value)
			{
				if($value != "")
				{
					$value = substr($value, 0, strpos($value, ";"));

					$pos = strpos($value, "=");

					$key = substr($value, 0, $pos);
					$val = substr($value, $pos+1);

					$this->cookie[$key] = $val;
				}
			}
		}

		function getCookie()
		{
			if( sizeof($this->cookie) == 0 )
				return "";

			$string = "Cookie: ";

			foreach($this->cookie as $key => $value)
				$string .= "$key=$value; ";

			return substr($string, 0, strlen($string)-2)."\r\n";
		}

		function setPost($param)
		{
			$this->method = "POST";
			$this->send_header["Content-Length"] = strlen($param);
			$this->send_parameter = $param;
		}

		function dumpAll()
		{
			$data = "<div style='text-align: justify; margin-bottom: 30px; font-size: 9pt; font-family: ±¼¸²; line-height: 150%; word-break: break-all'><fieldset><legend>&nbsp;Çì´õ&nbsp;</legend>";

			foreach($this->receive as $header => $value)
			{
				if( $header == "Body" )
					$body = "<fieldset><legend>&nbsp;º»¹®&nbsp;</legend>".htmlspecialchars($value)."</fieldset>";
				else
					$data .= "$header: ".htmlspecialchars($value)."<br>";
			}

			echo "$data</fieldset><fieldset><legend>&nbsp;ÄíÅ°&nbsp;</legend>";

			if( $this->cookie )
				foreach($this->cookie as $key => $value)
					echo "<b>$key</b>: ".htmlspecialchars(urldecode($value))."<br>";

			echo "</fieldset>$body</div>";
		}
	}

	function extString($string, $prefix, $postfix, $sequence=1)
	{
		for($i=0; $i<$sequence; $i++)
		{
			$pos_start = strpos($string, $prefix);

			if($pos_start === false)
				return false;

			$string = substr($string, $pos_start + strlen($prefix));
		}

		if($postfix !== "")
		{
			$pos_end = strpos($string, $postfix);

			if($pos_end === false)
				return false;

			$string = substr($string, 0, $pos_end);
		}

		return $string;
	}
?>