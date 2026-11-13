/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: tnet03
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `administradores`
--

DROP TABLE IF EXISTS `administradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `administradores` (
  `ID_ADMINISTRADOR` int(11) NOT NULL AUTO_INCREMENT,
  `NOME` char(100) NOT NULL,
  `EMAIL` char(100) NOT NULL,
  `SENHA` char(150) NOT NULL,
  `PERMISSAO` enum('Admin','Operador') DEFAULT NULL,
  `ATIVO` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`ID_ADMINISTRADOR`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administradores`
--

LOCK TABLES `administradores` WRITE;
/*!40000 ALTER TABLE `administradores` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `administradores` VALUES
(1,'Glauco Luiz Parquet de Campos','glauco.pcampos@sp.senac.br','$2y$10$ibSUiublg4zeZxjoqS12pOX0.InmBT4Q.1p0K9LFy/xMC05S3Nedq','Admin',1);
/*!40000 ALTER TABLE `administradores` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `ID_CLIENTE` int(11) NOT NULL AUTO_INCREMENT,
  `NOME` char(100) NOT NULL,
  `CPF` char(14) DEFAULT NULL,
  `LOGRADOURO` char(100) DEFAULT NULL,
  `NUMERO` char(15) DEFAULT NULL,
  `COMPLEMENTO` char(20) DEFAULT NULL,
  `BAIRRO` char(40) DEFAULT NULL,
  `CIDADE` char(40) DEFAULT NULL,
  `ESTADO` char(2) DEFAULT NULL,
  `CEP` char(9) DEFAULT NULL,
  PRIMARY KEY (`ID_CLIENTE`),
  UNIQUE KEY `CPF` (`CPF`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `clientes` VALUES
(1,'Ana Carolina Silva','111.111.111-01','Rua das Flores','100',NULL,'Centro','São Paulo','SP','01001-001'),
(2,'Bruno Oliveira Santos','222.222.222-02','Rua Augusta','250','Apto 12','Consolação','São Paulo','SP','01305-000'),
(3,'Carlos Eduardo Souza','333.333.333-03','Av. Paulista','1500','Sala 42','Bela Vista','São Paulo','SP','01310-100'),
(4,'Daniela Martins Costa','444.444.444-04','Rua Vergueiro','800',NULL,'Liberdade','São Paulo','SP','01504-000'),
(5,'Eduardo Pereira Lima','555.555.555-05','Rua Oscar Freire','321',NULL,'Pinheiros','São Paulo','SP','05409-010'),
(6,'Fernanda Alves Rocha','666.666.666-06','Av. Rebouças','900','Apto 81','Pinheiros','São Paulo','SP','05402-000'),
(7,'Gabriel Rodrigues','777.777.777-07','Rua Consolação','450',NULL,'Higienópolis','São Paulo','SP','01302-000'),
(8,'Helena Ferreira','888.888.888-08','Rua Haddock Lobo','700','Casa','Cerqueira César','São Paulo','SP','01414-000'),
(9,'Igor Mendes','999.999.999-09','Av. Ibirapuera','1200',NULL,'Moema','São Paulo','SP','04028-000'),
(10,'Juliana Ribeiro','000.000.000-10','Rua dos Pinheiros','580','Apto 33','Pinheiros','Taubaté','SP','05422-001'),
(11,'Glauco Luiz','123.456.789-10','Rua Teste','500','','Centro','Taubaté','SP','12010-000');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `compras`
--

DROP TABLE IF EXISTS `compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `compras` (
  `ID_COMPRA` int(11) NOT NULL AUTO_INCREMENT,
  `DATA` date NOT NULL,
  `FORMA_PGTO` enum('Pix','Crédito','Débito','Dinheiro') NOT NULL,
  `ID_FORNECEDOR` int(11) NOT NULL,
  `STATUS` enum('Pendente','Finalizado','Cancelado') NOT NULL DEFAULT 'Pendente',
  PRIMARY KEY (`ID_COMPRA`),
  KEY `ID_FORNECEDOR` (`ID_FORNECEDOR`),
  CONSTRAINT `fk_compras_fornecedores` FOREIGN KEY (`ID_FORNECEDOR`) REFERENCES `fornecedores` (`ID_FORNECEDOR`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras`
--

LOCK TABLES `compras` WRITE;
/*!40000 ALTER TABLE `compras` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `compras` VALUES
(1,'2026-08-01','Pix',1,'Pendente'),
(2,'2026-08-03','Crédito',2,'Pendente'),
(3,'2026-08-05','Débito',3,'Pendente'),
(4,'2026-08-07','Dinheiro',4,'Pendente'),
(5,'2026-08-10','Pix',1,'Pendente'),
(6,'2026-08-12','Crédito',2,'Pendente'),
(7,'2026-08-15','Débito',3,'Pendente'),
(8,'2026-08-18','Dinheiro',4,'Pendente'),
(9,'2026-08-20','Pix',1,'Pendente'),
(10,'2026-08-22','Crédito',2,'Pendente'),
(11,'2026-08-25','Débito',3,'Pendente'),
(12,'2026-08-27','Dinheiro',4,'Pendente'),
(13,'2026-09-01','Pix',1,'Pendente'),
(14,'2026-09-03','Crédito',2,'Pendente'),
(15,'2026-09-05','Débito',3,'Pendente'),
(16,'2026-09-08','Dinheiro',4,'Pendente'),
(17,'2026-09-10','Pix',1,'Pendente'),
(18,'2026-09-15','Crédito',2,'Pendente'),
(19,'2026-09-20','Débito',3,'Pendente'),
(20,'2026-10-23','Dinheiro',1,'Finalizado');
/*!40000 ALTER TABLE `compras` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedores` (
  `ID_FORNECEDOR` int(11) NOT NULL AUTO_INCREMENT,
  `RAZAO_SOCIAL` char(100) NOT NULL,
  `NOME_FANTASIA` char(100) NOT NULL,
  `CNPJ` char(18) NOT NULL,
  `TELEFONE` char(20) DEFAULT NULL,
  `EMAIL` char(100) DEFAULT NULL,
  PRIMARY KEY (`ID_FORNECEDOR`),
  UNIQUE KEY `RAZAO_SOCIAL` (`RAZAO_SOCIAL`,`CNPJ`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fornecedores`
--

LOCK TABLES `fornecedores` WRITE;
/*!40000 ALTER TABLE `fornecedores` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `fornecedores` VALUES
(1,'Tech Distribuidora LTDA','TechDist','11.111.111/0001-01','(11) 99999-0001','contato@techdist.com'),
(2,'Mega Informática LTDA','MegaInfo','22.222.222/0001-02','(11) 99999-0002','vendas@megainfo.com'),
(3,'Digital Solutions LTDA','DigitalSol','33.333.333/0001-03','(11) 99999-0003','contato@digitalsol.com'),
(4,'Comercial Brasil LTDA','ComercialBR','44.444.444/0001-04','(11) 99999-0004','vendas@comercialbr.com'),
(5,'Servico Nacional de Aprendizagem Comercial Senac','Senac Taubaté','03.709.814/0033-75','(12) 2125-6099','taubate@sp.senac.br');
/*!40000 ALTER TABLE `fornecedores` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `marcas` (
  `ID_MARCA` int(11) NOT NULL AUTO_INCREMENT,
  `MARCA` char(30) NOT NULL,
  PRIMARY KEY (`ID_MARCA`),
  UNIQUE KEY `MARCA` (`MARCA`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `marcas` VALUES
(1,'Acer'),
(2,'AMD'),
(3,'ASUS'),
(4,'Cooler Master'),
(5,'Corsair'),
(6,'Dell'),
(33,'Elgin'),
(7,'EVGA'),
(8,'Furukawa'),
(9,'HyperX'),
(10,'Intel'),
(11,'JBL'),
(12,'Kingston'),
(13,'Lenovo'),
(14,'LG'),
(15,'Logitech'),
(16,'Multilaser'),
(17,'NVIDIA'),
(18,'Razer'),
(19,'Redragon'),
(20,'Samsung'),
(21,'SanDisk'),
(22,'Seagate'),
(23,'SMS'),
(24,'TP-Link'),
(25,'Ugreen');
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `ID_PRODUTO` int(11) NOT NULL AUTO_INCREMENT,
  `PRODUTO` char(100) NOT NULL,
  `ID_MARCA` int(11) DEFAULT NULL,
  `DESCRICAO` text DEFAULT NULL,
  `PRECO_UNIT` decimal(8,2) NOT NULL,
  `QTDE` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID_PRODUTO`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `produtos` VALUES
(1,'Notebook',6,'Notebook para uso profissional',3599.90,25),
(2,'Notebook Gamer',1,'Notebook gamer de alto desempenho',5299.90,15),
(3,'Notebook Ultrafino',13,'Notebook leve e compacto',4199.90,20),
(4,'Desktop',6,'Computador desktop corporativo',2899.90,18),
(5,'Computador Gamer',1,'Computador gamer completo',6499.90,10),
(6,'Monitor 24\"',14,'Monitor Full HD 24 polegadas',899.90,30),
(7,'Monitor 27\"',20,'Monitor 27 polegadas',1399.90,20),
(8,'Monitor Ultrawide',14,'Monitor ultrawide 29 polegadas',1899.90,12),
(9,'Teclado USB',15,'Teclado USB padrão',129.90,80),
(10,'Teclado Mecânico',19,'Teclado mecânico RGB',349.90,40),
(11,'Mouse USB',15,'Mouse óptico USB',89.90,100),
(12,'Mouse Gamer',18,'Mouse gamer de alta precisão',299.90,50),
(13,'Headset',9,'Headset estéreo',249.90,60),
(14,'Headset Gamer',18,'Headset gamer com microfone',499.90,35),
(15,'Webcam Full HD',15,'Webcam Full HD',299.90,45),
(16,'Webcam 4K',15,'Webcam profissional 4K',799.90,15),
(17,'Microfone USB',9,'Microfone USB condensador',599.90,20),
(18,'Caixa de Som',11,'Caixa de som Bluetooth',399.90,30),
(19,'SSD 480GB',12,'SSD SATA 480GB',259.90,70),
(20,'SSD 1TB',12,'SSD NVMe 1TB',499.90,50),
(21,'SSD 2TB',20,'SSD NVMe 2TB',899.90,30),
(22,'HD 1TB',22,'HD SATA 1TB',299.90,40),
(23,'HD 2TB',22,'HD SATA 2TB',429.90,35),
(24,'HD Externo 1TB',26,'HD externo USB 1TB',399.90,25),
(25,'HD Externo 2TB',26,'HD externo USB 2TB',549.90,20),
(26,'Pen Drive 64GB',21,'Pen drive USB 64GB',59.90,100),
(27,'Pen Drive 128GB',21,'Pen drive USB 128GB',99.90,80),
(28,'Cartão SD 128GB',12,'Cartão microSD 128GB',119.90,60),
(29,'Memória 8GB',12,'Memória RAM DDR4 8GB',159.90,50),
(30,'Memória 16GB',12,'Memória RAM DDR4 16GB',299.90,40),
(31,'Memória 32GB',5,'Memória RAM DDR4 32GB',599.90,25),
(32,'Placa de Vídeo',17,'Placa de vídeo RTX',2499.90,10),
(33,'Placa-Mãe',3,'Placa-mãe para processadores modernos',899.90,20),
(34,'Processador',2,'Processador Ryzen',1299.90,15),
(35,'Processador Intel',10,'Processador Intel Core',1499.90,15),
(36,'Fonte 500W',5,'Fonte de alimentação 500W',299.90,30),
(37,'Fonte 650W',5,'Fonte de alimentação 650W',449.90,25),
(38,'Fonte 750W',7,'Fonte de alimentação 750W',599.90,20),
(39,'Gabinete',4,'Gabinete ATX',349.90,30),
(40,'Gabinete Gamer',19,'Gabinete gamer RGB',499.90,20),
(41,'Cooler CPU',4,'Cooler para processador',199.90,35),
(42,'Nobreak 1200VA',23,'Nobreak 1200VA',899.90,15),
(43,'Estabilizador',23,'Estabilizador 1000VA',299.90,25),
(44,'Roteador',24,'Roteador Wi-Fi',249.90,40),
(45,'Roteador Wi-Fi 6',24,'Roteador Wi-Fi 6',599.90,25),
(46,'Switch 8 Portas',24,'Switch de rede 8 portas',199.90,30),
(47,'Switch 24 Portas',24,'Switch de rede 24 portas',799.90,15),
(48,'Cabo HDMI',16,'Cabo HDMI 2 metros',49.90,100),
(49,'Cabo de Rede',8,'Cabo de rede CAT6',39.90,150),
(50,'Adaptador USB',25,'Adaptador USB-C',79.90,70);
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `rl_compras_produtos`
--

DROP TABLE IF EXISTS `rl_compras_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rl_compras_produtos` (
  `ID_COMPRA` int(11) NOT NULL,
  `ID_PRODUTO` int(11) NOT NULL,
  `PRECO_UNIT` decimal(8,2) NOT NULL,
  `QTDE` int(11) NOT NULL,
  KEY `ID_COMPRA` (`ID_COMPRA`),
  KEY `ID_PRODUTO` (`ID_PRODUTO`),
  CONSTRAINT `FK_RL_COMPRAS_PRODUTOS_COMPRAS` FOREIGN KEY (`ID_COMPRA`) REFERENCES `compras` (`ID_COMPRA`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_RL_COMPRAS_PRODUTOS_PRODUTOS` FOREIGN KEY (`ID_PRODUTO`) REFERENCES `produtos` (`ID_PRODUTO`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rl_compras_produtos`
--

LOCK TABLES `rl_compras_produtos` WRITE;
/*!40000 ALTER TABLE `rl_compras_produtos` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `rl_compras_produtos` VALUES
(1,1,3599.90,5),
(1,6,899.90,8),
(1,9,129.90,20),
(2,2,5299.90,3),
(2,10,349.90,15),
(2,12,299.90,10),
(2,14,499.90,8),
(3,19,259.90,30),
(3,20,499.90,15),
(3,29,159.90,25),
(4,32,2499.90,4),
(4,33,899.90,8),
(4,34,1299.90,6),
(4,39,349.90,10),
(5,22,299.90,20),
(5,23,429.90,15),
(5,24,399.90,12),
(6,15,299.90,20),
(6,16,799.90,5),
(6,17,599.90,8),
(7,44,249.90,15),
(7,45,599.90,10),
(7,46,199.90,12),
(7,49,39.90,50),
(8,26,59.90,80),
(8,27,99.90,60),
(8,28,119.90,50),
(9,30,299.90,25),
(9,31,599.90,10),
(9,35,1499.90,8),
(10,37,449.90,15),
(10,38,599.90,10),
(10,41,199.90,20),
(11,42,899.90,8),
(11,43,299.90,15),
(11,48,49.90,100),
(12,3,4199.90,4),
(12,7,1399.90,8),
(12,11,89.90,30),
(13,4,2899.90,6),
(13,8,1899.90,5),
(13,13,249.90,15),
(14,5,6499.90,2),
(14,32,2499.90,3),
(14,40,499.90,10),
(15,18,399.90,20),
(15,21,899.90,10),
(15,25,549.90,12),
(16,36,299.90,20),
(16,37,449.90,15),
(16,39,349.90,10),
(16,41,199.90,15),
(17,47,799.90,10),
(17,49,39.90,80),
(17,50,79.90,50),
(18,1,3599.90,3),
(18,20,499.90,15),
(18,32,2499.90,2),
(18,45,599.90,5),
(19,2,5299.90,2),
(19,30,299.90,20),
(19,35,1499.90,6),
(19,38,599.90,8),
(2,1,5299.90,1),
(20,38,599.90,25),
(20,31,599.90,50),
(20,32,2499.90,3);
/*!40000 ALTER TABLE `rl_compras_produtos` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `rl_vendas_produtos`
--

DROP TABLE IF EXISTS `rl_vendas_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rl_vendas_produtos` (
  `ID_VENDA` int(11) NOT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `PRECO_UNIT` decimal(8,2) NOT NULL,
  `QTDE` int(11) NOT NULL,
  KEY `ID_PRODUTO` (`id_produto`),
  KEY `ID_VENDA` (`ID_VENDA`),
  CONSTRAINT `fk_rl_vendas_produtos_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`ID_PRODUTO`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rl_vendas_produtos_vendas` FOREIGN KEY (`ID_VENDA`) REFERENCES `vendas` (`ID_VENDA`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rl_vendas_produtos`
--

LOCK TABLES `rl_vendas_produtos` WRITE;
/*!40000 ALTER TABLE `rl_vendas_produtos` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `rl_vendas_produtos` VALUES
(1,38,599.90,50),
(1,31,599.90,100),
(1,32,2499.90,5);
/*!40000 ALTER TABLE `rl_vendas_produtos` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `vendas`
--

DROP TABLE IF EXISTS `vendas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendas` (
  `ID_VENDA` int(11) NOT NULL AUTO_INCREMENT,
  `DATA` date NOT NULL,
  `FORMA_PGTO` enum('Pix','Débito','Crédito','Dinheiro') NOT NULL,
  `ID_CLIENTE` int(11) NOT NULL,
  `status` enum('Cancelado','Pendente','Finalizado') DEFAULT 'Pendente',
  PRIMARY KEY (`ID_VENDA`),
  KEY `ID_CLIENTE` (`ID_CLIENTE`),
  CONSTRAINT `fk_vendas_clientes` FOREIGN KEY (`ID_CLIENTE`) REFERENCES `clientes` (`ID_CLIENTE`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendas`
--

LOCK TABLES `vendas` WRITE;
/*!40000 ALTER TABLE `vendas` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `vendas` VALUES
(1,'2026-10-23','Pix',1,'Finalizado');
/*!40000 ALTER TABLE `vendas` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-11-05  8:09:06
