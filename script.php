<!DOCTYPE html>
<html>
	<head>
		<title>Script</title>
		<meta	charset="UTF-8">
		<meta name="Lucas" content="Lucas Jovencio">
	</head>
	<body>
		<?php
			set_time_limit(0);
			function query($n)
			{
				require_once('dependencia/query.php');
				$retorno = cypher_query($n);
				return $retorno;
			}
			function leitura($texto)
			{
				$lista = [];
				$cont = 0;
				$handle = @fopen("$texto.txt", "r");
				if ($handle) 
				{
					while (($buffer = fgets($handle, 4096)) !== false) 
					{
						$g= rtrim($buffer);
						$cont ++;
						$lista[$cont] = $g;
					}
					if (!feof($handle)) 
					{
						echo "Erro: falha inexperada de fgets()\n";
					}
					fclose($handle);
				}
				return $lista;
			}
			function padrao($lista)
			{
				$countM = count($lista);
				$query2 = '';
				for ($i = 1; $i <= $countM; $i++) 
				{
					$nome = $lista[$i];
					if ($nome != "")
					{
						$query2 = ("create (n".$i.":empresa{nome:'".$nome."'})");

						query($query2);
					}
				}
				$query = "create (n1:estado{nome:'São Paulo'}),(n2:estado{nome:'Espirito Santo'}),(n3:estado{nome:'Rio de Janeiro'}), 
						  (n4:pais{nome:'Brasil'}) ,(n1)-[:pais]->(n4),(n2)-[:pais]->(n4) , (n3)-[:pais]->(n4)";
				query($query);
				return $lista;
			}
			function relaciona($nome,$idade,$empresa,$opcao,$resi)
			{
				$query = ('match (estado:estado{nome:"'.$resi.'"}),(empresa:empresa{nome:"'.$empresa.'"})
								create (pessoa:pessoa{nome:"'.$nome.'",idade:"'.$idade.'"})
								merge (pessoa)-[:end]->(estado)
								merge (pessoa)-[:opcao{tipo:"'.$opcao.'"}]->(empresa)'
						  );
				return $query;
			}
			function maestro()
			{
				$listaEmpresas = padrao(leitura("empresas"));
				$listaNomes = leitura("nomes");
				
				$opcoes = ['Visitou','Trabalhou','Investiu'];
				$residente = ['Espirito Santo','São Paulo','Rio de Janeiro'];
				$countN = count($listaNomes);
				$countM = count($listaEmpresas);
				for ($i = 1; $i <= $countN; $i++) 
				{
					$nomePessoa = $listaNomes[$i];
					$idade = mt_rand(18, 50);
					$NumEmpresa = mt_rand(1, $countM);
					$nomeEmpresa = $listaEmpresas[$NumEmpresa];
					$numOPC = mt_rand(0, 2);
					$numResi = mt_rand(0, 2);
					if ($nomePessoa != '' && $nomeEmpresa != '')
					{
						query(relaciona($nomePessoa,$idade,$nomeEmpresa,$opcoes[$numOPC],$residente[$numResi]));
					}
				}
				$n = query('match (n:pessoa) return count(n)'); 
				foreach ($n as $c){
					$cont = $c['count(n)'];
					echo("<br><br><br><h1><center>Script Executado, $cont Pessoas Registradas e Relacionadas.</h1></center>");
				
				}
				
			}
			maestro();
		?>
	</body>
</html>
