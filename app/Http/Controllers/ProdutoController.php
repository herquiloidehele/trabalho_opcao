<?php

namespace App\Http\Controllers;

use App\Http\Controllers\classesAuxiliares\Auxiliar;
use App\Models\Produto;
use App\Models\Produz;
use Illuminate\Http\Request;
use Mockery\Exception;

class ProdutoController extends ModelController
{
    public function __construct() {
        $this->object = new Produto();
        $this->objectName = 'produto';
        $this->objectNames = 'produtos';
        $this->relactionships = ['categoria', 'produtores'];
    }


    /** @ApiDescription (section = Produtos, description="Busca todos os produtos existentes")
     * @ApiMethod(type="GET")
     * @ApiRoute(name="/api/produtos")
     * @ApiHeaders(name="Content-Type", type="application/json")
     */
    public function getAll(Request $request) {

        $produtos = json_decode($request->get('produtos'), true);


        if ($request->exists('pagination') and $request->get('pagination') > 0){
            return Auxiliar::retornarDados($this->objectNames, $this->object->with($this->relactionships)->orderBy('id','desc')
                ->paginate($request->input('pagination')), 200);
        }

        if($produtos){
            $produtosIds = $this->getProdutosIds($produtos);
            if(count($produtosIds) > 0){
                $produtosIds = implode(',', $produtosIds);
                return Auxiliar::retornarDados($this->objectNames, $this->object->with($this->relactionships)->orderByRaw(\DB::raw("FIELD(id, $produtosIds) DESC"))->get(), 200);
            }
        }

        return Auxiliar::retornarDados($this->objectNames, $this->object->with($this->relactionships)->orderBy('id','desc')->get(), 200);
    }


    /** @ApiDescription (section = Produtos, description="Cria um novo Produto")
     * @ApiMethod(type="Post")
     * @ApiRoute(name="/api/produtos")
     * @ApiHeaders(name="Content-Type", type="application/json")
     * @ApiParams(name="designacao", type="string", nullable=false)
     * @ApiParams(name="categorias_id", type="int", nullable=false)
     * @ApiParams(name="default_photo", type="string", nullable=false)
     */
    public function store(Request $request)
    {
        $produto_request =  $request->all('designacao', 'categoria', 'variedades');
        \DB::beginTransaction();


            $produto = Produto::create(
                [
                    'designacao' => $produto_request['designacao'],
                    'categoria_produtos_id' => $produto_request['categoria']['id']
                ]);

            if(!$produto){
                \DB::rollBack();
                throw new Exception('Erro ao criar um produto', 402);
            }

        \DB::commit();
            return ['produto' => $produto];

    }


    /** @ApiDescription (section = Produtos, description="Busca Um Determinado Produto existente")
     * @ApiMethod(type="GET")
     * @ApiParams(name="id", type="integer", nullable=false, description="User ID")
     * @ApiRoute(name="/api/produtos/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json")
     */
    public function get($id)
    {
        return parent::get($id);
    }


    /** @ApiDescription (section = Produtos, description="Actualiza um Produto")
     * @ApiMethod(type="put")
     * @ApiRoute(name="/api/produtos/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json")
     * @ApiParams(name="id", type="string", nullable=false, description="User ID")
     */
    public function update(Request $object, $id)
    {
        return parent::update($object, $id); // TODO: Change the autogenerated stub
    }


    /** @ApiDescription (section = Produtos, description="Elimina um Produto")
     * @ApiMethod(type="delete")
     * @ApiRoute(name="/api/produtos/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json")
     * @ApiParams(name="id", type="string", nullable=false, description="User ID")
     */
    public function destroy(Request $objecto, $id)
    {
        return parent::destroy($objecto, $id); // TODO: Change the autogenerated stub
    }


    public function getProdutosIds($produtos){
        $ids = collect($produtos)->map(function($produto){
           return $produto['id'];
        });

        return $ids->all();
    }

}
