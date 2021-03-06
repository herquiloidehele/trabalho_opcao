<?php

namespace App\Http\Controllers\interfaces;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Esta interface eh generica para dos os controllers da aplicacao
 * User: herquiloidehele
 * Date: 10/1/17
 * Time: 17:52
 */

interface InterfaceController{


    /**
     * funcao que busca lista de objectos
     * @param $utilimo - (True or false) Ultimo ou nao
     * @param $request - especifica se deve ser retornado o objecto com todos
     * os seus objectos relacionados.
     * @return lista de todos objectos
     */
    public function getAll(Request $request);


    /**
     * @param $id - do objecto pesquisado
     * @return $objecto encontrado
     */
    public function get($id);


    /**
     * Salvar um determinado Objecto
     * @param Request $objecto - objecto a ser salvo
     * @return $objecto - objecto se for salvo
     */
    public function store(Request $objecto);


    /**
     * @param Request $object - O objecto a ser actualizado
     * @param $id - a chave primaria do objecto
     * @return $objecto se for actualizado
     */
    public function update(Request $object, $id);


    /**
     * @param Request $objecto - O objecto a ser removido
     * @param $id - a chave primaria do objecto
     * @return $objecto se for removido
     */
    public function destroy(Request $objecto, $id);


    /**
     * funcao que salva um conjunto de objectos numa transacao
     * em um eh dependente do outro
     * @param Request[] $objectos - conjunto de objectos a serem salvos
     * @return $object - conjunto de objectos salvos
     */
    public function saveTransactions(Request $objectos);


    /**
     * funcao que pesquisa baseado em varios atributos
     * @param array $atributos - conjunto de atributos que serao usados para a pesquisa
     * @return $objecto retornado
     */
    public function searchMany(...$atributos);


    /**
     * busca o ultimo objecto a se adicionado
     * @return $object - ultimo objecto adicionado
     */
    public function getLast();


}
















