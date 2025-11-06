# MVC com PHP

[![GitHub license](https://img.shields.io/github/license/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP)
[![GitHub stars](https://img.shields.io/github/stars/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP/network)
[![GitHub issues](https://img.shields.io/github/issues/clcmo/MVCcomPHP?style=for-the-badge)](https://github.com/clcmo/MVCcomPHP/issues)
[![GitHub donate](https://img.shields.io/github/sponsors/clcmo?color=pink&style=for-the-badge)](https://github.com/sponsors/clcmo)


## 📁 Estrutura do Sistema:

### **Arquivo 1: Sistema Principal** (config.php + classes)
Contém:
- ✅ **Classe Database** - Com método `createDatabase()` para criar BD e tabelas
- ✅ **Método `insertSampleData()`** - Insere dados de exemplo
- ✅ **Classe Aula** - CRUD completo + navegação entre aulas
- ✅ **Classe Comentario** - Gerenciamento de comentários
- ✅ **install.php** - Interface visual para instalação

### **Arquivo 2: Páginas** (index.php + aula.php)
Contém:
- ✅ **index.php** - Lista todas as aulas com design moderno
- ✅ **aula.php** - Visualização completa da aula com comentários
- ✅ Navegação entre aulas (anterior/próxima)
- ✅ Atividade recente na sidebar

## 🚀 Como Instalar:

### **Passo 1:** Salvar os arquivos
```
projeto/
├── config.php (com todas as classes)
├── install.php (página de instalação)
├── index.php (lista de aulas)
└── aula.php (página da aula)
```

### **Passo 2:** Configurar credenciais
Edite em `config.php`:
```php
private $host = 'localhost';
private $username = 'root';
private $password = ''; // Sua senha do MySQL
```

### **Passo 3:** Executar instalação
1. Acesse: `http://localhost/seu_projeto/install.php`
2. O sistema irá:
   - ✅ Criar banco de dados `sistema_aulas`
   - ✅ Criar 3 tabelas (usuarios, aulas, comentarios)
   - ✅ Inserir dados de exemplo (1 professora, 4 alunos, 8 aulas, 9 comentários)
3. Clicar em "Ir para o Sistema"

### **Passo 4:** Usar o sistema
- Acesse `index.php` para ver todas as aulas
- Clique em qualquer aula para ver conteúdo completo
- Comente nas aulas (sistema simula login automático)

## ✨ Recursos Implementados:

- 🔐 **Criação automática do BD** via PHP
- 📊 **8 aulas completas** de PHP com conteúdo real
- 💬 **Sistema de comentários** funcional
- 🎨 **Design moderno** e responsivo
- ⬅️➡️ **Navegação** entre aulas
- 📈 **Dashboard** com estatísticas
- 🔒 **Segurança**: PDO, prepared statements, htmlspecialchars
- 📱 **Responsivo** para mobile

**Dados de teste incluídos:**
- Email: `joao@aluno.com` / Senha: `senha123`

O sistema está 100% funcional e pronto para uso! 🎉

### Pré-requisitos
PHP, SQL e servidor Apache instalados (por meio do WAMP/XAMP ou Laragon)

## Contribuindo
Instruções para contribuir com o projeto.

## Licença
Licença [MIT](LICENSE)
