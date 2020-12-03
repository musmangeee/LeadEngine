<script>
import { Bar, mixins } from "vue-chartjs";
const { reactiveProp } = mixins
export default {
  name : 'LeadsVolumeChart',
  props: ["options"],
  extends: Bar,
  mixins: [reactiveProp],
  mounted() {
    this.renderChart(this.chartData, this.options);
  }
};
</script>

<style>
</style>