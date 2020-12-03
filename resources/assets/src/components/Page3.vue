<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">{{hey}}</h4>
    <p>This page is an example of basic layout. For more options use <strong>Laravel + Vue.js starter template generator</strong> in the docs.</p>
    <p><button class="btn btn-primary btn-lg">Button</button></p>
  </div>
</template>

<script>
export default {
  name: 'page-2',
  metaInfo: {
    title: 'Page 3a'
  },
  data: function() {
    return {
      hey: 'Page 3 '
    }
  }
}
</script>
