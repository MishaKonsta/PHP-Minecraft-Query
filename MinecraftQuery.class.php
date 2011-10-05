<?php
	/*
	 * Queries Minecraft server (1.8+)
	 *
	 * @author xPaw
	 * @link https://github.com/xPaw/PHP-Minecraft-Query
	 *
	 * @param $Ip - server ip
	 * @param $Port - server port
	 *
	 * @return Array on success, false on failure.
	 */
	function QueryMinecraft( $IP, $Port = 25565 )
	{
		$Socket = Socket_Create( AF_INET, SOCK_STREAM, SOL_TCP );
		
		if( $Socket === FALSE || @Socket_Connect( $Socket, $IP, (int)$Port ) === FALSE )
		{
			return FALSE;
		}
		
		Socket_Send( $Socket, "\xFE", 1, 0 );
		$Len = Socket_Recv( $Socket, $Data, 100, 0 );
		Socket_Close( $Socket );
		
		if( $Len < 4 || $Data[ 0 ] != "\xFF" )
		{
			return FALSE;
		}
		
		$Data = SubStr( $Data, 3 );
		$Data = iconv( 'UTF-16BE', 'UTF-8', $Data );
		$Data = Explode( "\xA7", $Data );
		
		return Array(
			'HostName'   => Trim( SubStr( $Data[ 0 ], 0, -1 ) ),
			'Players'    => isset( $Data[ 1 ] ) ? IntVal( $Data[ 1 ] ) : 0,
			'MaxPlayers' => isset( $Data[ 2 ] ) ? IntVal( $Data[ 2 ] ) : 0
		);
	}
?>