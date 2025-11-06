# 📚 Sistema de Aulas PHP - MVC Modular

[![GitHub license](https://img.shields.io/github/license/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP)
[![GitHub stars](https://img.shields.io/github/stars/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP/network)
[![GitHub issues](https://img.shields.io/github/issues/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP/issues)
[![GitHub donate](https://img.shields.io/github/sponsors/clcmo?color=pink&style=for-the-badge)](https://github.com/sponsors/clcmo)

Sistema completo de gestão de aulas desenvolvido em PHP com arquitetura MVC modular.

## ✨ Funcionalidades

- ✅ CRUD completo de aulas
- ✅ Sistema de comentários
- ✅ Navegação entre aulas
- ✅ Dashboard com estatísticas
- ✅ Design responsivo e moderno
- ✅ Instalador automático
- ✅ Arquitetura MVC profissional

## 🚀 Tecnologias

- PHP 7.4+
- MySQL 5.7+
- PDO para banco de dados
- Autoloading PSR-4
- CSS3 com Grid e Flexbox

## 📦 Instalação

### 1. Clone o repositório

```bash
git clone https://github.com/clcmo/MVCcomPHP.git
cd MVCcomPHP
```

### 2. Configure o banco de dados

Edite o arquivo `.env`:
```env
DB_HOST=localhost
DB_NAME=sistema_aulas
DB_USER=root
DB_PASS=sua_senha
```

### 3. Execute o instalador

Acesse: `http://localhost/MVCcomPHP/public/install`

### 4. Acesse o sistema

`http://localhost/MVCcomPHP/public/`

## 📁 Estrutura do Projeto

```
MVCcomPHP/
├── app/
│   ├── Controllers/    # Lógica de negócio
│   ├── Models/         # Camada de dados
│   ├── Views/          # Interface do usuário
│   ├── Core/           # Classes fundamentais
│   ├── Config/         # Configurações
│   └── Helpers/        # Funções auxiliares
├── public/             # Arquivos públicos
│   ├── assets/         # CSS, JS, imagens
│   └── index.php       # Ponto de entrada
└── storage/            # Logs e cache
```

## 🎯 Uso

### Listar todas as aulas

```php
$aulaController = new AulaController();
$aulaController->index();
```

### Ver uma aula específica

```php
$aulaController->show($id);
```

### Adicionar comentário

```php
$aulaController->addComment();
```

## 🛠️ Desenvolvimento

### Adicionar nova rota

```php
// public/index.php
$router->get('/nova-rota', 'SeuController', 'suaAction');
```

### Criar novo Model

```php
namespace App\Models;

use App\Core\Database;

class SeuModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
}
```

## 📖 Documentação

- [Guia de Contribuição](CONTRIBUTING.md)
- [Licença MIT](LICENSE)

## 👤 Autor

**clcmo**

- GitHub: [@clcmo](https://github.com/clcmo)

## 📄 Licença

Este projeto está sob a licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.