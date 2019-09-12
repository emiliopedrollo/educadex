<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Escola
 *
 * @property int $nu_ano_censo
 * @property int $co_entidade
 * @property string $no_entidade
 * @property string|null $co_orgao_regional
 * @property string|null $tp_situacao_funcionamento
 * @property string|null $dt_ano_letivo_inicio
 * @property string|null $dt_ano_letivo_termino
 * @property string|null $co_regiao
 * @property string|null $co_mesorregiao
 * @property string|null $co_microrregiao
 * @property int $co_uf
 * @property int $co_municipio
 * @property string|null $co_distrito
 * @property string|null $tp_dependencia
 * @property string|null $tp_localizacao
 * @property string|null $tp_categoria_escola_privada
 * @property string|null $in_conveniada_pp
 * @property string|null $tp_convenio_poder_publico
 * @property string|null $in_mant_escola_privada_emp
 * @property string|null $in_mant_escola_privada_ong
 * @property string|null $in_mant_escola_privada_sind
 * @property string|null $in_mant_escola_privada_sist_s
 * @property string|null $in_mant_escola_privada_s_fins
 * @property string|null $co_escola_sede_vinculada
 * @property string|null $co_ies_ofertante
 * @property string|null $tp_regulamentacao
 * @property string|null $in_local_func_predio_escolar
 * @property string|null $tp_ocupacao_predio_escolar
 * @property string|null $in_local_func_salas_empresa
 * @property string|null $in_local_func_socioeducativo
 * @property string|null $in_local_func_unid_prisional
 * @property string|null $in_local_func_prisional_socio
 * @property string|null $in_local_func_templo_igreja
 * @property string|null $in_local_func_casa_professor
 * @property string|null $in_local_func_galpao
 * @property string|null $tp_ocupacao_galpao
 * @property string|null $in_local_func_salas_outra_esc
 * @property string|null $in_local_func_outros
 * @property string|null $in_predio_compartilhado
 * @property string|null $in_agua_filtrada
 * @property string|null $in_agua_rede_publica
 * @property string|null $in_agua_poco_artesiano
 * @property string|null $in_agua_cacimba
 * @property string|null $in_agua_fonte_rio
 * @property string|null $in_agua_inexistente
 * @property string|null $in_energia_rede_publica
 * @property string|null $in_energia_gerador
 * @property string|null $in_energia_outros
 * @property string|null $in_energia_inexistente
 * @property string|null $in_esgoto_rede_publica
 * @property string|null $in_esgoto_fossa
 * @property string|null $in_esgoto_inexistente
 * @property string|null $in_lixo_coleta_periodica
 * @property string|null $in_lixo_queima
 * @property string|null $in_lixo_joga_outra_area
 * @property string|null $in_lixo_recicla
 * @property string|null $in_lixo_enterra
 * @property string|null $in_lixo_outros
 * @property string|null $in_sala_diretoria
 * @property string|null $in_sala_professor
 * @property string|null $in_laboratorio_informatica
 * @property string|null $in_laboratorio_ciencias
 * @property string|null $in_sala_atendimento_especial
 * @property string|null $in_quadra_esportes_coberta
 * @property string|null $in_quadra_esportes_descoberta
 * @property string|null $in_quadra_esportes
 * @property string|null $in_cozinha
 * @property string|null $in_biblioteca
 * @property string|null $in_sala_leitura
 * @property string|null $in_biblioteca_sala_leitura
 * @property string|null $in_parque_infantil
 * @property string|null $in_bercario
 * @property string|null $in_banheiro_fora_predio
 * @property string|null $in_banheiro_dentro_predio
 * @property string|null $in_banheiro_ei
 * @property string|null $in_banheiro_pne
 * @property string|null $in_dependencias_pne
 * @property string|null $in_secretaria
 * @property string|null $in_banheiro_chuveiro
 * @property string|null $in_refeitorio
 * @property string|null $in_despensa
 * @property string|null $in_almoxarifado
 * @property string|null $in_auditorio
 * @property string|null $in_patio_coberto
 * @property string|null $in_patio_descoberto
 * @property string|null $in_alojam_aluno
 * @property string|null $in_alojam_professor
 * @property string|null $in_area_verde
 * @property string|null $in_lavanderia
 * @property string|null $in_dependencias_outras
 * @property string|null $qt_salas_existentes
 * @property string|null $qt_salas_utilizadas
 * @property string|null $in_equip_tv
 * @property string|null $in_equip_videocassete
 * @property string|null $in_equip_dvd
 * @property string|null $in_equip_parabolica
 * @property string|null $in_equip_copiadora
 * @property string|null $in_equip_retroprojetor
 * @property string|null $in_equip_impressora
 * @property string|null $in_equip_impressora_mult
 * @property string|null $in_equip_som
 * @property string|null $in_equip_multimidia
 * @property string|null $in_equip_fax
 * @property string|null $in_equip_foto
 * @property string|null $in_computador
 * @property string|null $qt_equip_tv
 * @property string|null $qt_equip_videocassete
 * @property string|null $qt_equip_dvd
 * @property string|null $qt_equip_parabolica
 * @property string|null $qt_equip_copiadora
 * @property string|null $qt_equip_retroprojetor
 * @property string|null $qt_equip_impressora
 * @property string|null $qt_equip_impressora_mult
 * @property string|null $qt_equip_som
 * @property string|null $qt_equip_multimidia
 * @property string|null $qt_equip_fax
 * @property string|null $qt_equip_foto
 * @property string|null $qt_computador
 * @property string|null $qt_comp_administrativo
 * @property string|null $qt_comp_aluno
 * @property string|null $in_internet
 * @property string|null $in_banda_larga
 * @property string|null $qt_funcionarios
 * @property string|null $in_alimentacao
 * @property string|null $tp_aee
 * @property string|null $tp_atividade_complementar
 * @property string|null $in_fundamental_ciclos
 * @property string|null $tp_localizacao_diferenciada
 * @property string|null $in_material_esp_quilombola
 * @property string|null $in_material_esp_indigena
 * @property string|null $in_material_esp_nao_utiliza
 * @property string|null $in_educacao_indigena
 * @property string|null $tp_indigena_lingua
 * @property string|null $co_lingua_indigena
 * @property string|null $in_brasil_alfabetizado
 * @property string|null $in_final_semana
 * @property string|null $in_formacao_alternancia
 * @property string|null $in_mediacao_presencial
 * @property string|null $in_mediacao_semipresencial
 * @property string|null $in_mediacao_ead
 * @property string|null $in_especial_exclusiva
 * @property string|null $in_regular
 * @property string|null $in_eja
 * @property string|null $in_profissionalizante
 * @property string|null $in_comum_creche
 * @property string|null $in_comum_pre
 * @property string|null $in_comum_fund_ai
 * @property string|null $in_comum_fund_af
 * @property string|null $in_comum_medio_medio
 * @property string|null $in_comum_medio_integrado
 * @property string|null $in_comum_medio_normal
 * @property string|null $in_esp_exclusiva_creche
 * @property string|null $in_esp_exclusiva_pre
 * @property string|null $in_esp_exclusiva_fund_ai
 * @property string|null $in_esp_exclusiva_fund_af
 * @property string|null $in_esp_exclusiva_medio_medio
 * @property string|null $in_esp_exclusiva_medio_integr
 * @property string|null $in_esp_exclusiva_medio_normal
 * @property string|null $in_comum_eja_fund
 * @property string|null $in_comum_eja_medio
 * @property string|null $in_comum_eja_prof
 * @property string|null $in_esp_exclusiva_eja_fund
 * @property string|null $in_esp_exclusiva_eja_medio
 * @property string|null $in_esp_exclusiva_eja_prof
 * @property string|null $in_comum_prof
 * @property string|null $in_esp_exclusiva_prof
 * @method static Builder|Escola newModelQuery()
 * @method static Builder|Escola newQuery()
 * @method static Builder|Escola query()
 * @method static Builder|Escola whereCoDistrito($value)
 * @method static Builder|Escola whereCoEntidade($value)
 * @method static Builder|Escola whereCoEscolaSedeVinculada($value)
 * @method static Builder|Escola whereCoIesOfertante($value)
 * @method static Builder|Escola whereCoLinguaIndigena($value)
 * @method static Builder|Escola whereCoMesorregiao($value)
 * @method static Builder|Escola whereCoMicrorregiao($value)
 * @method static Builder|Escola whereCoMunicipio($value)
 * @method static Builder|Escola whereCoOrgaoRegional($value)
 * @method static Builder|Escola whereCoRegiao($value)
 * @method static Builder|Escola whereCoUf($value)
 * @method static Builder|Escola whereDtAnoLetivoInicio($value)
 * @method static Builder|Escola whereDtAnoLetivoTermino($value)
 * @method static Builder|Escola whereInAguaCacimba($value)
 * @method static Builder|Escola whereInAguaFiltrada($value)
 * @method static Builder|Escola whereInAguaFonteRio($value)
 * @method static Builder|Escola whereInAguaInexistente($value)
 * @method static Builder|Escola whereInAguaPocoArtesiano($value)
 * @method static Builder|Escola whereInAguaRedePublica($value)
 * @method static Builder|Escola whereInAlimentacao($value)
 * @method static Builder|Escola whereInAlmoxarifado($value)
 * @method static Builder|Escola whereInAlojamAluno($value)
 * @method static Builder|Escola whereInAlojamProfessor($value)
 * @method static Builder|Escola whereInAreaVerde($value)
 * @method static Builder|Escola whereInAuditorio($value)
 * @method static Builder|Escola whereInBandaLarga($value)
 * @method static Builder|Escola whereInBanheiroChuveiro($value)
 * @method static Builder|Escola whereInBanheiroDentroPredio($value)
 * @method static Builder|Escola whereInBanheiroEi($value)
 * @method static Builder|Escola whereInBanheiroForaPredio($value)
 * @method static Builder|Escola whereInBanheiroPne($value)
 * @method static Builder|Escola whereInBercario($value)
 * @method static Builder|Escola whereInBiblioteca($value)
 * @method static Builder|Escola whereInBibliotecaSalaLeitura($value)
 * @method static Builder|Escola whereInBrasilAlfabetizado($value)
 * @method static Builder|Escola whereInComputador($value)
 * @method static Builder|Escola whereInComumCreche($value)
 * @method static Builder|Escola whereInComumEjaFund($value)
 * @method static Builder|Escola whereInComumEjaMedio($value)
 * @method static Builder|Escola whereInComumEjaProf($value)
 * @method static Builder|Escola whereInComumFundAf($value)
 * @method static Builder|Escola whereInComumFundAi($value)
 * @method static Builder|Escola whereInComumMedioIntegrado($value)
 * @method static Builder|Escola whereInComumMedioMedio($value)
 * @method static Builder|Escola whereInComumMedioNormal($value)
 * @method static Builder|Escola whereInComumPre($value)
 * @method static Builder|Escola whereInComumProf($value)
 * @method static Builder|Escola whereInConveniadaPp($value)
 * @method static Builder|Escola whereInCozinha($value)
 * @method static Builder|Escola whereInDependenciasOutras($value)
 * @method static Builder|Escola whereInDependenciasPne($value)
 * @method static Builder|Escola whereInDespensa($value)
 * @method static Builder|Escola whereInEducacaoIndigena($value)
 * @method static Builder|Escola whereInEja($value)
 * @method static Builder|Escola whereInEnergiaGerador($value)
 * @method static Builder|Escola whereInEnergiaInexistente($value)
 * @method static Builder|Escola whereInEnergiaOutros($value)
 * @method static Builder|Escola whereInEnergiaRedePublica($value)
 * @method static Builder|Escola whereInEquipCopiadora($value)
 * @method static Builder|Escola whereInEquipDvd($value)
 * @method static Builder|Escola whereInEquipFax($value)
 * @method static Builder|Escola whereInEquipFoto($value)
 * @method static Builder|Escola whereInEquipImpressora($value)
 * @method static Builder|Escola whereInEquipImpressoraMult($value)
 * @method static Builder|Escola whereInEquipMultimidia($value)
 * @method static Builder|Escola whereInEquipParabolica($value)
 * @method static Builder|Escola whereInEquipRetroprojetor($value)
 * @method static Builder|Escola whereInEquipSom($value)
 * @method static Builder|Escola whereInEquipTv($value)
 * @method static Builder|Escola whereInEquipVideocassete($value)
 * @method static Builder|Escola whereInEsgotoFossa($value)
 * @method static Builder|Escola whereInEsgotoInexistente($value)
 * @method static Builder|Escola whereInEsgotoRedePublica($value)
 * @method static Builder|Escola whereInEspExclusivaCreche($value)
 * @method static Builder|Escola whereInEspExclusivaEjaFund($value)
 * @method static Builder|Escola whereInEspExclusivaEjaMedio($value)
 * @method static Builder|Escola whereInEspExclusivaEjaProf($value)
 * @method static Builder|Escola whereInEspExclusivaFundAf($value)
 * @method static Builder|Escola whereInEspExclusivaFundAi($value)
 * @method static Builder|Escola whereInEspExclusivaMedioIntegr($value)
 * @method static Builder|Escola whereInEspExclusivaMedioMedio($value)
 * @method static Builder|Escola whereInEspExclusivaMedioNormal($value)
 * @method static Builder|Escola whereInEspExclusivaPre($value)
 * @method static Builder|Escola whereInEspExclusivaProf($value)
 * @method static Builder|Escola whereInEspecialExclusiva($value)
 * @method static Builder|Escola whereInFinalSemana($value)
 * @method static Builder|Escola whereInFormacaoAlternancia($value)
 * @method static Builder|Escola whereInFundamentalCiclos($value)
 * @method static Builder|Escola whereInInternet($value)
 * @method static Builder|Escola whereInLaboratorioCiencias($value)
 * @method static Builder|Escola whereInLaboratorioInformatica($value)
 * @method static Builder|Escola whereInLavanderia($value)
 * @method static Builder|Escola whereInLixoColetaPeriodica($value)
 * @method static Builder|Escola whereInLixoEnterra($value)
 * @method static Builder|Escola whereInLixoJogaOutraArea($value)
 * @method static Builder|Escola whereInLixoOutros($value)
 * @method static Builder|Escola whereInLixoQueima($value)
 * @method static Builder|Escola whereInLixoRecicla($value)
 * @method static Builder|Escola whereInLocalFuncCasaProfessor($value)
 * @method static Builder|Escola whereInLocalFuncGalpao($value)
 * @method static Builder|Escola whereInLocalFuncOutros($value)
 * @method static Builder|Escola whereInLocalFuncPredioEscolar($value)
 * @method static Builder|Escola whereInLocalFuncPrisionalSocio($value)
 * @method static Builder|Escola whereInLocalFuncSalasEmpresa($value)
 * @method static Builder|Escola whereInLocalFuncSalasOutraEsc($value)
 * @method static Builder|Escola whereInLocalFuncSocioeducativo($value)
 * @method static Builder|Escola whereInLocalFuncTemploIgreja($value)
 * @method static Builder|Escola whereInLocalFuncUnidPrisional($value)
 * @method static Builder|Escola whereInMantEscolaPrivadaEmp($value)
 * @method static Builder|Escola whereInMantEscolaPrivadaOng($value)
 * @method static Builder|Escola whereInMantEscolaPrivadaSFins($value)
 * @method static Builder|Escola whereInMantEscolaPrivadaSind($value)
 * @method static Builder|Escola whereInMantEscolaPrivadaSistS($value)
 * @method static Builder|Escola whereInMaterialEspIndigena($value)
 * @method static Builder|Escola whereInMaterialEspNaoUtiliza($value)
 * @method static Builder|Escola whereInMaterialEspQuilombola($value)
 * @method static Builder|Escola whereInMediacaoEad($value)
 * @method static Builder|Escola whereInMediacaoPresencial($value)
 * @method static Builder|Escola whereInMediacaoSemipresencial($value)
 * @method static Builder|Escola whereInParqueInfantil($value)
 * @method static Builder|Escola whereInPatioCoberto($value)
 * @method static Builder|Escola whereInPatioDescoberto($value)
 * @method static Builder|Escola whereInPredioCompartilhado($value)
 * @method static Builder|Escola whereInProfissionalizante($value)
 * @method static Builder|Escola whereInQuadraEsportes($value)
 * @method static Builder|Escola whereInQuadraEsportesCoberta($value)
 * @method static Builder|Escola whereInQuadraEsportesDescoberta($value)
 * @method static Builder|Escola whereInRefeitorio($value)
 * @method static Builder|Escola whereInRegular($value)
 * @method static Builder|Escola whereInSalaAtendimentoEspecial($value)
 * @method static Builder|Escola whereInSalaDiretoria($value)
 * @method static Builder|Escola whereInSalaLeitura($value)
 * @method static Builder|Escola whereInSalaProfessor($value)
 * @method static Builder|Escola whereInSecretaria($value)
 * @method static Builder|Escola whereNoEntidade($value)
 * @method static Builder|Escola whereNuAnoCenso($value)
 * @method static Builder|Escola whereQtCompAdministrativo($value)
 * @method static Builder|Escola whereQtCompAluno($value)
 * @method static Builder|Escola whereQtComputador($value)
 * @method static Builder|Escola whereQtEquipCopiadora($value)
 * @method static Builder|Escola whereQtEquipDvd($value)
 * @method static Builder|Escola whereQtEquipFax($value)
 * @method static Builder|Escola whereQtEquipFoto($value)
 * @method static Builder|Escola whereQtEquipImpressora($value)
 * @method static Builder|Escola whereQtEquipImpressoraMult($value)
 * @method static Builder|Escola whereQtEquipMultimidia($value)
 * @method static Builder|Escola whereQtEquipParabolica($value)
 * @method static Builder|Escola whereQtEquipRetroprojetor($value)
 * @method static Builder|Escola whereQtEquipSom($value)
 * @method static Builder|Escola whereQtEquipTv($value)
 * @method static Builder|Escola whereQtEquipVideocassete($value)
 * @method static Builder|Escola whereQtFuncionarios($value)
 * @method static Builder|Escola whereQtSalasExistentes($value)
 * @method static Builder|Escola whereQtSalasUtilizadas($value)
 * @method static Builder|Escola whereTpAee($value)
 * @method static Builder|Escola whereTpAtividadeComplementar($value)
 * @method static Builder|Escola whereTpCategoriaEscolaPrivada($value)
 * @method static Builder|Escola whereTpConvenioPoderPublico($value)
 * @method static Builder|Escola whereTpDependencia($value)
 * @method static Builder|Escola whereTpIndigenaLingua($value)
 * @method static Builder|Escola whereTpLocalizacao($value)
 * @method static Builder|Escola whereTpLocalizacaoDiferenciada($value)
 * @method static Builder|Escola whereTpOcupacaoGalpao($value)
 * @method static Builder|Escola whereTpOcupacaoPredioEscolar($value)
 * @method static Builder|Escola whereTpRegulamentacao($value)
 * @method static Builder|Escola whereTpSituacaoFuncionamento($value)
 * @mixin Eloquent
 */
class Escola extends Model
{

    public function municipio() {
        return $this->belongsTo(Municipio::class,'co_municipio','co_municipio')
            ->orderByRaw('qt_populacao DESC NULLS LAST');
    }

    public function estado () {
        return $this->belongsTo(UF::class,'co_uf','co_uf');
    }



}
