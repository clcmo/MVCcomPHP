<?php
namespace Tests;

use App\Models\Aula;
use PHPUnit\Framework\TestCase;

class AulaTest extends TestCase {
    
    private $aulaModel;
    
    protected function setUp(): void {
        $this->aulaModel = new Aula();
    }
    
    public function testGetAll() {
        $aulas = $this->aulaModel->getAll();
        
        $this->assertIsArray($aulas);
        $this->assertNotEmpty($aulas);
        $this->assertArrayHasKey('id', $aulas[0]);
        $this->assertArrayHasKey('titulo', $aulas[0]);
    }
    
    public function testGetById() {
        $aula = $this->aulaModel->getById(1);
        
        $this->assertIsArray($aula);
        $this->assertEquals(1, $aula['id']);
        $this->assertNotEmpty($aula['titulo']);
    }
    
    public function testCreate() {
        $data = [
            'titulo' => 'Aula de Teste',
            'descricao' => 'Descrição de teste',
            'conteudo' => 'Conteúdo de teste',
            'codigo_exemplo' => '<?php echo "teste"; ?>',
            'professor_id' => 1,
            'ordem' => 999
        ];
        
        $result = $this->aulaModel->create($data);
        $this->assertTrue($result);
    }
    
    public function testGetNext() {
        $proxima = $this->aulaModel->getNext(1);
        
        $this->assertIsArray($proxima);
        $this->assertGreaterThan(1, $proxima['id']);
    }
    
    public function testGetPrevious() {
        $anterior = $this->aulaModel->getPrevious(3);
        
        $this->assertIsArray($anterior);
        $this->assertLessThan(3, $anterior['id']);
    }
}