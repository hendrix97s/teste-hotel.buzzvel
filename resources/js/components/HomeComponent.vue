<template>
  <div class="container-fluid">
    <div class="row">

      <div class="col-5">
        <div class="card m-3">
          <div class="card-body">
            <div class="input-group mb-3">

              <input type="text" class="form-control" placeholder="Digite local, que buscamos os hoteis para você" aria-label="Recipient's username" aria-describedby="search" v-model="formSearch.address">

              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-search"></i>
                </button>
                <div class="dropdown-menu">
                  <button class="dropdown-item" @click="search('pricepernight')">Buscar ordenado por preço?</button>
                  <button class="dropdown-item" @click="search('proximity')">Buscar ordenado por proximidade?</button>
                </div>
              </div>
            </div>

            <div class="alert alert-warning" role="alert" v-if="warning">
              <b><i class="bi bi-emoji-smile-upside-down"></i> Ops</b>, insira um local válido.
            </div>

            <div class="alert alert-danger" role="alert" v-if="danger">
              <b><i class="bi bi-emoji-frown"></i> Nos desculpe</b>, não encontramos nenhum hotel em nossos registros proximo dessa região. Tente novamente.
            </div>

            <div class="alert alert-success" role="alert" v-if="success">
             <b><i class="bi bi-emoji-sunglasses"></i> hurruu!</b> hotéis encontrados com sucesso <b>{{userName}}</b>. Aproveite!
            </div>
          </div>
        </div>

        <div class="jumbotron m-3">
          <h1 class="display-4">Olá {{userName}} <i class="bi bi-emoji-smile"></i></h1>
          <p class="lead">
            Quer viajar, mas não sabe onde se hospedar?
          </p>
          <p>
            A <b>Th Buzzvel</b> pode te ajudar com isso, basta pesquisar por uma cidade, endereço, estado ou país que nós lhe mostramos as melhores opções de hotéis para você. 
          </p>
        </div>
      </div>


      <div class="col-7 list-hotels row">
        <div class="d-flex justify-content-center loading col-12" v-if="loading">
          <div class="spinner-border text-info" role="status" style="width: 6rem; height: 6rem;">
            <span class="sr-only">Loading...</span>
          </div>
        </div>

        <div class="card card-hotel col-12 mb-3" v-for="hotel in listHotels" :key="hotel.id">
          <div class="row no-gutters">
            <div class="col-md-3">
              <i class="fas fa-hotel" style="font-size:92px; margin-top:32px; margin-left: 22px;color:#2da4df"></i>
              <!-- <img src="" alt=""> -->
            </div>
            <div class="col-md-9">
              <div class="card-body">
                <h5 class="card-title"><b>Hotel: {{hotel.name}}</b></h5>
                <p class="card-text"> <b>Distância:</b> {{hotel.distance}} KM. </p>
                <p class="card-text"> <b>Preço por noite: {{hotel.price}} EUR</b> </p>
                <p class="card-text"><small class="text-muted"><b>Tempo de viagem do ponto de origem até o hotel:</b> {{hotel.duration}}</small></p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  components: {},
  props: ["name"],

  data() {
    return {
      userName: "",
      listHotels:[],
      config: {
        headers: {
          'Accept' : 'application/json',
          'Content-Type' : 'application/json',
        }
      },
      formSearch:{
        orderby:'',
        address:''
      },
      loading: false,
      warning: false,
      danger : false,
      success: false
    };
  },
  mounted() {
    this.userName = this.name.split(" ")[0]
  },
  methods: {
    // Função para buscar a listagem de hoténs de acordo com o local informado
    search(orderby){
      this.formSearch.orderby = orderby
      if(this.formSearch.address.length >= 3){

        this.warning = false
        this.loading = true
        this.danger = false
        this.success = false
        this.listHotels = []

        axios.post("/search",JSON.stringify(this.formSearch),this.config)
        .then(response => {

          if(response.data.erro == 'endereco inexistente' ){
            this.warning = true
            console.log(response.data.erro);
          }else if(response.data.erro == 'sem hoteis'){
            this.danger = true
          }
          else{
            this.success = true
            this.listHotels = response.data
          }
        }).catch(erro => {
          
        }).finally(() => this.loading = false)
      }else {
        this.warning = true
      }
    }//fim de search
  },
  beforeMount() {},
};
</script>
