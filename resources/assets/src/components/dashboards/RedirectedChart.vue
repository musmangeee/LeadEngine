<script>
import { Pie, mixins } from "vue-chartjs";
const { reactiveProp } = mixins
export default {
  name : 'RedirectedChart',
  props: ["options"],
  extends: Pie,
  mixins: [reactiveProp],
  mounted() {
    this.renderChart(this.chartData, this.options);
  }
};
</script>

<style>
</style>