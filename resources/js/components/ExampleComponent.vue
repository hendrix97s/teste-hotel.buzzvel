<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h1>Card Example</h1>
          </div>

          <div class="card-body">
            {{ example }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  props: ["data"],

  data() {
    return {
      example: "example component",
    };
  },
  mounted() {},
  methods: {
    acao() {},
  },
  beforeMount() {},
};
</script>
