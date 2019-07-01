<?php

namespace App\Http\Controllers;

use App\Http\Controllers\classesAuxiliares\Auxiliar;
use App\Models\Categoria;
use App\Models\Procura;
use App\Models\Produto;
use App\Models\Produtore;
use App\Models\Revendedore;
use App\Models\UnidadesMedida;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use DB;

class ProcuraController extends ModelController
{
    public function __construct() {
        $this->object = new Procura();
        $this->objectName = 'procura';
        $this->objectNames = 'procuras';
        $this->relactionships = ['distrito', 'produto', 'unidades_medida', 'revendedore' => function($query) {$query->with('user');}];
    }


    /**
     * @ApiDescription (section = Procuras, description="Retorna todos Procuras")
     * @ApiMethod(type="get")
     * @ApiRoute(name="/procuras")
     * @ApiHeaders(name="Content-Type", type="application/json", descriptoin="Tipo de conteudo")
     */
    public function getAll(Request $request)
    {
        return parent::getAll($request); // TODO: Change the autogenerated stub
    }

    /**
     * @ApiDescription (section = Procuras, description="Busca Uma Determinada Procuras existente")
     * @ApiMethod(type="GET")
     * @ApiParams(name="id", type="integer", nullable=false, description="Procuras ID")
     * @ApiRoute(name="/procuras/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json", description="Tipo de Conteudo")
     */
    public function get($id)
    {
        return parent::get($id);
    }


    /**
     * @ApiDescription (section = Procuras, description="Cria uma nova Procura")
     * @ApiMethod(type="POST")
     * @ApiRoute(name="/procuras")
     * @ApiHeaders(name="Content-Type", type="application/json", description="Tipo de Conteudo")
     * @ApiParams(name="designacao", type="string", nullable=false, description="Designacao")
     * @ApiParams(name="revendedores_id", type="integer", nullable=false, description="Id do revendedor")
     * @ApiParams(name="unidades_medidas_id", type="integer", nullable=false, description="Id da Uniade de Medida")
     * @ApiParams(name="produtos_id", type="integer", nullable=false, description="Id do Produto")
     * @ApiParams(name="quantidade", type="double", nullable=false, description="Quantidade procurada")
     * @ApiParams(name="distritos_id", type="integer", nullable=false, description="Id do distrito em que se localiza o revendedor")
     */
    public function store(Request $request)
    {
        return parent::store($request); // TODO: Change the autogenerated stub
    }

    /** @ApiDescription (section = Procuras, description="Actualiza uma Procuras")
     * @ApiMethod(type="put")
     * @ApiRoute(name="/procuras/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json", description="Tipo de Conteudo")
     * @ApiParams(name="id", type="integer", nullable=false, description="Procuras ID")
     */
    public function update(Request $object, $id)
    {
        return parent::update($object, $id); // TODO: Change the autogenerated stub
    }


    /** @ApiDescription (section = Procuras, description="Elimina uma Procuras")
     * @ApiMethod(type="delete")
     * @ApiRoute(name="/procuras/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json", description="Tipo de Conteudo")
     * @ApiParams(name="id", type="integer", nullable=false, description="Procuras ID")
     */
    public function destroy(Request $objecto, $id)
    {
        return parent::destroy($objecto, $id); // TODO: Change the autogenerated stub
    }





    /**
     * @ApiDescription (section = "Requisições", description="Retorna as requisicoes feitas para o produtor de produtos de acordo com os seus interesses")
     * @ApiMethod(type="GET")
     * @ApiParams(name="id", type="integer", nullable=false, description="Id do User")
     * @ApiRoute(name="/procuras/produtos-produtor")
     * @ApiHeaders(name="Content-Type", type="application/json", )
     */
    public function getAllOfProdutos(Request $request){
        $produtor = User::find($request->id)->produtor;

        $revendedorProcuras = $this->getProcura();
        $produtorProducoes = $this->getProdutosDoProdutor($produtor->id);

        $requisicoesProdutos = collect();


        foreach ($revendedorProcuras as $revendedores){
            foreach ($revendedores['procura'] as $procura){
                if($this->getProdutosRequisitados($produtorProducoes, $procura))
                    $requisicoesProdutos->push(['revendedor' => $revendedores['revendedor'], 'procura' => $procura]);
            }
        }


        return $requisicoesProdutos;
    }



    public function getProcurasSemelhantes($id){
        $produto = Produto::find(Procura::where('id', '=', $id)->first()['produtos_id']);

        $procuras = Procura::with(['distrito', 'produto', 'unidades_medida',
                'revendedore' => function($query) {$query->with('user');}])
                            ->select('procuras.*')
                            ->join('produtos', 'produtos.id', '=', 'procuras.produtos_id')
                            ->where('produtos.categorias_id', '=', $produto['categorias_id'])
                            ->get();

        return ['procuras' => $procuras];
    }

    /**
     * compara os produtos que o produtor produz e os produtos que os mercado disponibilizam
     * @param $produtorProducao
     * @param $procura
     * @return bool
     */
    private function getProdutosRequisitados($produtorProducao, $procura){

        foreach ($produtorProducao['produz'] as $produtorProduz) {
            if ($produtorProduz['produto']['designacao'] == $procura['produto']['designacao'])
//                if($produtorProduz['unidade_medida']['designacao'] == $procura['unidade_medida']['designacao'])
                    return true;
        }

        return false;

    }

    /**
     *Retorna a lista de revendedores e os produtos que eles procuram ou requisitam
     * @param $produto_id
     * @param $produtosQueProduz
     * @return array
     */
    private function getProcura(){
        $revendedores = Revendedore::all();
        $revendedorProcura = collect();

        foreach ($revendedores as $revendedor){
            $produtos = collect(Revendedore::find($revendedor->id)->procuras);


            $procura = collect();


            foreach ($produtos->all() as $produto){
                $procura->push(
                    [
                        'id' => $produto->id,
                        'produto' => $produto,
                        'unidade_medida' => UnidadesMedida::find($produto->pivot->unidades_medidas_id),
                        'quantidade' => $produto->pivot->quantidade,
                        'data_formatada' => $produto->pivot->created_at->diffForHumans(),
                        'data_pura' => $produto->created_at

                    ]);
            }

            $revendedorProcura->push(['revendedor' => Revendedore::find($revendedor->id), 'procura' => $procura]);
        }

        return $revendedorProcura;
    }


    /**
     * @ApiDescription (section = "Produtores", description="Retorna os produtos que um determinado produtor produz")
     * @ApiMethod(type="GET")
     * @ApiParams(name="id", type="integer", nullable=false, description="Id do Produtor")
     * @ApiRoute(name="/produz/produtor-producao/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json", description="Tipo de Conteudo")
     */
    private function getProdutosDoProdutor($produtor_id){
        $produtos = collect(Produtore::find($produtor_id)->produtosQueProduz);
        $prodQueProdutorProduz = collect();


        foreach ($produtos->all() as $produto){
            $prodQueProdutorProduz->push([
                'produto' => $produto,
                'unidade_medida' => UnidadesMedida::find($produto->pivot->unidades_medidas_id),
                'quantidade' => $produto->pivot->quantidade_media
            ]);
        }

        return ['produtor' => Produtore::find($produtor_id)->first(), 'produzs' => $prodQueProdutorProduz ];

    }


    /**
     * @ApiDescription (section = "Procuras", description="Retorna os produtos requisitados para um determinado produtor")
     * @ApiMethod(type="GET")
     * @ApiParams(name="id", type="integer", nullable=false, description="Id do Produtor")
     * @ApiRoute(name="/procuras/produtores/{id}")
     * @ApiHeaders(name="Content-Type", type="application/json", description="Tipo de Conteudo")
     * @param $id
     * @return array
     */
    public function getProcurasProdutor($id){
        $produtor = Produtore::with(['distrito', 'produzs'])->where('id', '=', $id)->first();

        $produtosId = implode(',', $this->getProdutosId($produtor->produzs));
        $distrito_id = $produtor->distrito->id . '';

        return [ 'procuras' => Procura::with(
            [
            'distrito',
            'produto',
            'unidades_medida',
            'revendedore' => function($query) {$query->with('user');}
            ])
            ->orderByRaw(DB::raw("FIELD(produtos_id, $produtosId) DESC"))
            ->orderByRaw(DB::raw("FIELD(distritos_id, $distrito_id) DESC"))
            ->get()
            ];

    }


    /**
     * Retorna os ids dos produtos produzidos pelo produtor
     * @param $produz
     * @return array
     */
    private function getProdutosId($produz){
        if(count($produz) == 0)
            return [];

        $produzCollect = collect($produz);
        $produtosId = $produzCollect->map(function ($produto) {
            return $produto['produtos_id'];
        });

        return $produtosId->all();
    }




}
