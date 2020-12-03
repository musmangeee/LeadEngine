<template>
  <div>
    <h4 class="font-weight-bold py-3 mb-4">
      Portfolio
      <b-btn
        v-if="ability.can('create', 'Portfolio')"
        variant="btn btn-primary"
        class="float-right"
        @click="createPortfolio"
      >
        <i class="fas fa-plus-circle"></i> Add New
      </b-btn>
    </h4>

    <b-card>
      <hr class="border-light m-0" />

      <div class="table-responsive">
        <b-table
          :fields="portfolioListFields"
          :items="portfolioList"
          :show-empty="true"
          :empty-text="'Portfolio data not available.'"
        >
          <template v-slot:cell(name)="data">{{ data.item.name }}</template>
          <template v-slot:cell(description)="data">
            <span v-if="data.item.description">
              {{ data.item.description }}
            </span>
            <span v-else> - </span>
          </template>

          <template v-slot:cell(status)="data">
            <span
              v-if="data.item.status == 'active'"
              class="badge badge-success"
              >{{ data.item.status }}</span
            >
            <span
              v-if="data.item.status == 'inactive'"
              class="badge badge-secondary"
              >{{ data.item.status }}</span
            >
          </template>

          <!-- A custom formatted column -->
          <template v-slot:cell(actions)="data">
            <b-btn
              v-if="ability.can('update', 'Portfolio')"
              @click="editPortfolio(data.item)"
              variant="outline-dark btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Edit"
            >
              <i class="fas fa-pencil-alt"></i>
            </b-btn>

            <b-btn
              v-if="ability.can('delete', 'Portfolio')"
              @click="deletePortfolio(data.item)"
              variant="outline-danger btn-xs icon-btn md-btn-flat"
              v-b-tooltip.hover
              title="Delete"
            >
              <i class="fas fa-trash"></i>
            </b-btn>
          </template>
        </b-table>
      </div>

      <div class="row">
        <div class="col">
          <b-form inline class="pt-1">
            <label>Per Page:</label>
            <b-select
              class="ml-1"
              v-model="portfolioListMeta.perPage"
              :options="[10, 25, 50, 100]"
            />
          </b-form>
        </div>
        <div class="col">
          <b-pagination
            align="right"
            v-model="portfolioListMeta.currentPage"
            :total-rows="portfolioListMeta.total"
            :per-page="portfolioListMeta.perPage"
            aria-controls="portfolio-list-table"
          ></b-pagination>
        </div>
      </div>

    </b-card>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  name: "PortfolioList",
  created() {
    this.loadPortfolioList();
  },
  data: () => ({
    portfolioListFields: [
      {
        key: "portfolio_key",
        label: "Portfolio UUID",
        sortable: false,
        tdClass: "align-middle",
      },
      {
        key: "name",
        label: "Portfolio Name",
        sortable: false,
        tdClass: "align-middle",
      },
      {
        key: "description",
        label: "Portfolio Description",
        sortable: false,
        tdClass: "align-middle",
      },
      {
        key: "status",
        label: "Status",
        sortable: false,
        tdClass: "align-middle",
      },
      {
        key: "actions",
        label: "Actions",
        sortable: false,
        tdClass: "text-nowrap align-middle text-center",
      },
    ],
  }),
  computed: {
    ...mapState("portfolios", ["portfolioList", "portfolioListMeta"]),
    ability() {
      return window.ability;
    },
  },
  watch: {
    "portfolioListMeta.currentPage"() {
      this.loadPortfolioList();
    },
    "portfolioListMeta.perPage"() {
      this.resetPortfolioListPage();
      this.loadPortfolioList();
    },
  },
  methods: {
    loadPortfolioList() {
      this.$store.dispatch("portfolios/portfolioList");
    },
    async resetPortfolioListPage() {
      await this.$store.dispatch("portfolios/resetPortfolioListPage");
    },
    createPortfolio() {
      this.$router.push({ name: "portfolio-create" });
    },
    editPortfolio(portfolio) {
      this.$router.push({
        name: "portfolio-edit",
        params: { id: portfolio.id },
      });
    },
    deletePortfolio(portfolio) {
      let self = this;
      this.$swal({
        title: `Are you sure you want to delete Portfolio ${portfolio.name}?`,
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete",
      }).then((result) => {
        if (result.value) {
          self.$store.dispatch("portfolios/portfolioDelete", {
            id: portfolio.id,
          });
          self.$snotify.success("Portfolio Deleted", "Success!");
        }
      });
    },
  },
};
</script>
