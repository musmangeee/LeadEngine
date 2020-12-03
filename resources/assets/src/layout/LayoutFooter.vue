<template>
  <nav class="layout-footer footer" :class="getLayoutFooterBg()">

    <div class="container-fluid container-p-x pb-3">
        <b-row>
            <div class="col-12 col-md-7 pt-3 text-center text-md-left" style="font-size:12px">
                ⓒ 2020 AIM Lead Engine | All rights reserved.
            </div>
        </b-row>
    </div>


  </nav>
</template>

<script>
export default {
  name: 'app-layout-footer',

  methods: {
    getLayoutFooterBg () {
      return `bg-${this.layoutFooterBg}`
    }
  }
}
</script>
