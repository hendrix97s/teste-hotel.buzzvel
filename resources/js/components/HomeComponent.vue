<template>
  <div class="container-fluid">
    <div class="row">

      <div class="col-5">
        <div class="card m-3">
          <div class="card-body">
            <div class="input-group mb-3">

              <input type="text" class="form-control" placeholder="Digite local, que buscamos os hoteis para você" aria-label="Recipient's username" aria-describedby="search">


              <div class="input-group-append">
     
                <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bi bi-search"></i>
                </button>
                <div class="dropdown-menu">
                  <button class="dropdown-item" @click="search('pricepernight')">Buscar por preço por noite?</button>
                  <button class="dropdown-item" @click="search('proximity')">Buscar por proximidade?</button>
                </div>
              </div>


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
          <hr class="my-4">
          <!-- <p>It uses utility classes for typography and spacing to space content out within the larger container.</p> -->
          <!-- <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a> -->
        </div>
      </div>


      <div class="col-7 list-hotels">
        <div class="d-flex justify-content-center loading">
          <div class="spinner-grow text-info" role="status" style="width: 6rem; height: 6rem;">
            <span class="sr-only">Loading...</span>
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
      example: "example component",
      userName: ""
    };
  },
  mounted() {
    this.userName = this.name.split(" ")[0]
  },
  methods: {
    acao() {},
    search(orderby){
      console.log(orderby);
    }
  },
  beforeMount() {},
};
</script>
