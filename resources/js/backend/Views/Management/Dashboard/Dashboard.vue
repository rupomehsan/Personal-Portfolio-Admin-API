<template>
  <div class="dashboard-container" :class="{ 'dark-theme': isDarkMode }">
    <!-- Top KPI Cards -->
    <div class="row g-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon product-views">
            <i class="fas fa-cube"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">$24,92</h3>
            <p class="stat-label">Product Views</p>
            <span class="stat-change positive">
              <i class="fas fa-arrow-up"></i> 2.2%
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon product-sold">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">8,430</h3>
            <p class="stat-label">Product Sold</p>
            <span class="stat-change negative">
              <i class="fas fa-arrow-down"></i> 0.5%
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon total-likes">
            <i class="fas fa-thumbs-up"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">46,256</h3>
            <p class="stat-label">Total Likes</p>
            <span class="stat-change positive">
              <i class="fas fa-arrow-up"></i> 1.2%
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon conversation">
            <i class="fas fa-comments"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">$200,56</h3>
            <p class="stat-label">Conversation</p>
            <span class="stat-change positive">
              <i class="fas fa-arrow-up"></i> 3.2%
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row g-4 mb-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon product-views">
            <i class="fas fa-cube"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">$24,92</h3>
            <p class="stat-label">Product Views</p>
            <span class="stat-change positive">
              <i class="fas fa-arrow-up"></i> 2.2%
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon product-sold">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">8,430</h3>
            <p class="stat-label">Product Sold</p>
            <span class="stat-change negative">
              <i class="fas fa-arrow-down"></i> 0.5%
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon total-likes">
            <i class="fas fa-thumbs-up"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">46,256</h3>
            <p class="stat-label">Total Likes</p>
            <span class="stat-change positive">
              <i class="fas fa-arrow-up"></i> 1.2%
            </span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-icon conversation">
            <i class="fas fa-comments"></i>
          </div>
          <div class="stat-content">
            <h3 class="stat-value">$200,56</h3>
            <p class="stat-label">Conversation</p>
            <span class="stat-change positive">
              <i class="fas fa-arrow-up"></i> 3.2%
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
      <!-- Store Analytics -->
      <div class="col-12 col-lg-8">
        <div class="dashboard-card">
          <div class="card-header">
            <h5 class="card-title">Store Analytics</h5>
            <div class="chart-legend">
              <span class="legend-item">
                <span class="legend-dot visitors"></span> Visitors
              </span>
              <span class="legend-item">
                <span class="legend-dot orders"></span> Orders
              </span>
            </div>
          </div>
          <div class="card-body">
            <canvas
              ref="storeAnalyticsChart"
              style="max-height: 300px"
            ></canvas>
          </div>
        </div>
      </div>

      <!-- Active Users -->
      <div class="col-12 col-lg-4">
        <div class="dashboard-card">
          <div class="card-header">
            <h5 class="card-title">Active Users</h5>
          </div>
          <div
            class="card-body d-flex justify-content-center align-items-center"
            style="min-height: 300px"
          >
            <canvas
              ref="activeUsersChart"
              style="width: 250px; height: 250px"
            ></canvas>
          </div>
          <div class="card-footer">
            <div class="user-stats">
              <div class="stat-item">
                <span class="stat-dot" style="background: #8b5cf6"></span>
                <span>55.6% Desktop</span>
              </div>
              <div class="stat-item">
                <span class="stat-dot" style="background: #fbbf24"></span>
                <span>33.3% Mobile</span>
              </div>
              <div class="stat-item">
                <span class="stat-dot" style="background: #f87171"></span>
                <span>11.1% Tablet</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Second Row: Maps and Products -->
    <div class="row g-4 mb-4">
      <!-- Sales by Location -->
      <div class="col-12 col-lg-6">
        <div class="dashboard-card">
          <div class="card-header">
            <h5 class="card-title">Sales By Location</h5>
            <select class="form-select-sm">
              <option>Top Countries</option>
            </select>
          </div>
          <div class="card-body">
            <div class="location-map">
              <svg viewBox="0 0 960 500" class="world-map">
                <!-- Simplified world map visualization -->
                <text x="100" y="150" class="country-name">United States</text>
                <text x="100" y="180" class="percentage">50%</text>
                <rect
                  x="20"
                  y="190"
                  width="150"
                  height="12"
                  class="progress-bar"
                  style="background: #3b82f6"
                ></rect>

                <text x="600" y="250" class="country-name">Australia</text>
                <text x="600" y="280" class="percentage">35%</text>
                <rect
                  x="520"
                  y="290"
                  width="105"
                  height="12"
                  class="progress-bar"
                  style="background: #10b981"
                ></rect>

                <text x="100" y="350" class="country-name">Brazil</text>
                <text x="100" y="380" class="percentage">22%</text>
                <rect
                  x="20"
                  y="390"
                  width="66"
                  height="12"
                  class="progress-bar"
                  style="background: #8b5cf6"
                ></rect>

                <text x="400" y="100" class="country-name">Germany</text>
                <text x="400" y="130" class="percentage">52%</text>
                <rect
                  x="320"
                  y="140"
                  width="156"
                  height="12"
                  class="progress-bar"
                  style="background: #f59e0b"
                ></rect>
              </svg>
            </div>
            <div class="location-stats">
              <div class="location-item">
                <div class="flag">🇺🇸</div>
                <span>United States - 50%</span>
              </div>
              <div class="location-item">
                <div class="flag">🇦🇺</div>
                <span>Australia - 35%</span>
              </div>
              <div class="location-item">
                <div class="flag">🇧🇷</div>
                <span>Brazil - 22%</span>
              </div>
              <div class="location-item">
                <div class="flag">🇩🇪</div>
                <span>Germany - 52%</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="col-12 col-lg-6">
        <div class="row g-4">
          <!-- Top Selling Product -->
          <div class="col-12">
            <div class="dashboard-card">
              <div class="card-header">
                <h5 class="card-title">Top Selling Product</h5>
                <div class="period-toggle">
                  <button class="period-btn active">Today</button>
                  <button class="period-btn">Month</button>
                </div>
              </div>
              <div class="card-body">
                <div class="product-showcase">
                  <div class="product-image">
                    <img
                      src="https://via.placeholder.com/100x100?text=Sunglass"
                      alt="Product"
                    />
                  </div>
                  <div class="product-info">
                    <h6 class="product-name">Stylish Sunglass</h6>
                    <p class="product-offer">
                      New offer only <strong>$26.00</strong>
                    </p>
                    <div class="product-rating">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Most Viewed Product -->
          <div class="col-12">
            <div class="dashboard-card">
              <div class="card-header">
                <h5 class="card-title">Most Viewed Product</h5>
                <div class="period-toggle">
                  <button class="period-btn active">Today</button>
                  <button class="period-btn">Month</button>
                </div>
              </div>
              <div class="card-body">
                <div class="product-showcase">
                  <div class="product-image">
                    <img
                      src="https://via.placeholder.com/100x100?text=Shoes"
                      alt="Product"
                    />
                  </div>
                  <div class="product-info">
                    <h6 class="product-name">Trending Oz Pro Shoes</h6>
                    <p class="product-offer">
                      New offer only <strong>$105.00</strong>
                    </p>
                    <div class="product-rating">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star-half-alt"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Metrics Section -->
    <div class="row g-4 mb-4">
      <!-- Monthly Campaign State -->
      <div class="col-12 col-lg-6">
        <div class="dashboard-card">
          <div class="card-header">
            <h5 class="card-title">Monthly Campaign State</h5>
            <span class="email-count">34.25k email sent</span>
          </div>
          <div class="card-body">
            <div class="metrics-grid">
              <div class="metric-item">
                <div class="metric-icon retargeted">
                  <i class="fas fa-redo"></i>
                </div>
                <div class="metric-info">
                  <h6>Retargeted</h6>
                  <p class="metric-value">14,679</p>
                  <span class="metric-change positive"
                    ><i class="fas fa-arrow-up"></i> 20%</span
                  >
                </div>
              </div>

              <div class="metric-item">
                <div class="metric-icon opened">
                  <i class="fas fa-external-link-alt"></i>
                </div>
                <div class="metric-info">
                  <h6>Opened</h6>
                  <p class="metric-value">12,675</p>
                  <span class="metric-change positive"
                    ><i class="fas fa-arrow-up"></i> 35%</span
                  >
                </div>
              </div>

              <div class="metric-item">
                <div class="metric-icon clicked">
                  <i class="fas fa-hand-pointer"></i>
                </div>
                <div class="metric-info">
                  <h6>Clicked</h6>
                  <p class="metric-value">6,320</p>
                  <span class="metric-change negative"
                    ><i class="fas fa-arrow-down"></i> 10%</span
                  >
                </div>
              </div>

              <div class="metric-item">
                <div class="metric-icon complaints">
                  <i class="fas fa-comment-slash"></i>
                </div>
                <div class="metric-info">
                  <h6>Complaints</h6>
                  <p class="metric-value">575</p>
                  <span class="metric-change negative"
                    ><i class="fas fa-arrow-down"></i> 2%</span
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Source Visitors -->
      <div class="col-12 col-lg-6">
        <div class="dashboard-card">
          <div class="card-header">
            <h5 class="card-title">Source Visitors</h5>
            <span class="visitor-count">70.8k Visitors</span>
          </div>
          <div class="card-body">
            <div class="source-list">
              <div class="source-item">
                <div class="source-icon social">
                  <i class="fas fa-share-alt"></i>
                </div>
                <div class="source-info">
                  <h6>Social Network</h6>
                  <p class="visitor-text">31.4k</p>
                </div>
                <span class="change negative"
                  ><i class="fas fa-arrow-down"></i> 20%</span
                >
              </div>

              <div class="source-item">
                <div class="source-icon direct">
                  <i class="fas fa-user-circle"></i>
                </div>
                <div class="source-info">
                  <h6>Direct Source</h6>
                  <p class="visitor-text">1.4k</p>
                </div>
                <span class="change positive"
                  ><i class="fas fa-arrow-up"></i> 15%</span
                >
              </div>

              <div class="source-item">
                <div class="source-icon advt">
                  <i class="fas fa-project-diagram"></i>
                </div>
                <div class="source-info">
                  <h6>ADVT</h6>
                  <p class="visitor-text">31.2k</p>
                </div>
                <span class="change positive"
                  ><i class="fas fa-arrow-up"></i> 16%</span
                >
              </div>

              <div class="source-item">
                <div class="source-icon referral">
                  <i class="fas fa-users"></i>
                </div>
                <div class="source-info">
                  <h6>Referrals</h6>
                  <p class="visitor-text">345</p>
                </div>
                <span class="change negative"
                  ><i class="fas fa-arrow-down"></i> 8%</span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Sellers Table -->
    <div class="row g-4">
      <div class="col-12">
        <div class="dashboard-card">
          <div class="card-header">
            <h5 class="card-title">Top Sellers</h5>
          </div>
          <div class="table-responsive">
            <table class="sellers-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Product</th>
                  <th>Country</th>
                  <th>Quantity</th>
                  <th>Total</th>
                  <th>Revenue</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="seller-info">
                      <img
                        src="https://via.placeholder.com/40x40?text=WW"
                        alt="Wade Warren"
                      />
                      <div>
                        <h6>Wade Warren</h6>
                        <p class="company">Apple Store Online</p>
                      </div>
                    </div>
                  </td>
                  <td>07 August</td>
                  <td>T-shirt</td>
                  <td><span class="flag">🇦🇪</span> UAE</td>
                  <td>2865</td>
                  <td>$5,08,876</td>
                  <td><strong>$27,187</strong></td>
                </tr>
                <tr>
                  <td>
                    <div class="seller-info">
                      <img
                        src="https://via.placeholder.com/40x40?text=AB"
                        alt="Afrad Bhuyian"
                      />
                      <div>
                        <h6>Afrad Bhuyian</h6>
                        <p class="company">Acme Corporation</p>
                      </div>
                    </div>
                  </td>
                  <td>25 Mar</td>
                  <td>Earings</td>
                  <td><span class="flag">🇺🇸</span> USA</td>
                  <td>36654</td>
                  <td>$3,06,867</td>
                  <td><strong>$23.89</strong></td>
                </tr>
                <tr>
                  <td>
                    <div class="seller-info">
                      <img
                        src="https://via.placeholder.com/40x40?text=RF"
                        alt="Robert Fox"
                      />
                      <div>
                        <h6>Robert Fox</h6>
                        <p class="company">Omega Solutions</p>
                      </div>
                    </div>
                  </td>
                  <td>14 February</td>
                  <td>1 phone</td>
                  <td><span class="flag">🇬🇧</span> UK</td>
                  <td>4253</td>
                  <td>$1,06,657</td>
                  <td><strong>$56.99</strong></td>
                </tr>
                <tr>
                  <td>
                    <div class="seller-info">
                      <img
                        src="https://via.placeholder.com/40x40?text=JW"
                        alt="John William"
                      />
                      <div>
                        <h6>John William</h6>
                        <p class="company">Phoenix Electronics</p>
                      </div>
                    </div>
                  </td>
                  <td>17 January</td>
                  <td>Watches</td>
                  <td><span class="flag">🇩🇪</span> GER</td>
                  <td>3532</td>
                  <td>$3,54,450</td>
                  <td><strong>$7,67,657</strong></td>
                </tr>
                <tr>
                  <td>
                    <div class="seller-info">
                      <img
                        src="https://via.placeholder.com/40x40?text=AI"
                        alt="Ahmed Imtiaz"
                      />
                      <div>
                        <h6>Ahmed Imtiaz</h6>
                        <p class="company">Thunderbolt Inc</p>
                      </div>
                    </div>
                  </td>
                  <td>11 November</td>
                  <td>Shoes</td>
                  <td><span class="flag">🇧🇷</span> BRA</td>
                  <td>9076</td>
                  <td>$9,54,450</td>
                  <td><strong>$78.01</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Chart from "chart.js/auto";

export default {
  name: "Dashboard",
  data() {
    return {
      data: {},
      storeAnalyticsChart: null,
      activeUsersChart: null,
      isDarkMode: false,
    };
  },
  mounted() {
    this.initializeDarkMode();
    this.initCharts();
    this.get_all_dashboard_data();
  },
  methods: {
    initializeDarkMode() {
      // Use global ThemeManager if available
      if (window.themeManager) {
        this.isDarkMode = window.themeManager.isDarkTheme();

        // Listen to global theme changes
        window.addEventListener("themechange", (event) => {
          this.isDarkMode = event.detail.theme === "dark-theme";
          this.reinitCharts();
        });
      }
    },

    toggleDarkMode() {
      // Use global ThemeManager to toggle
      if (window.themeManager) {
        window.themeManager.toggleTheme();
      }
    },

    reinitCharts() {
      this.initCharts();
    },

    get_all_dashboard_data: async function () {
      try {
        let response = await axios.get("get-all-dashboard-data");
        if (response.status == 200) {
          this.data = response.data.data;
        }
      } catch (error) {
        console.error("Error fetching dashboard data:", error);
      }
    },

    initCharts() {
      this.initStoreAnalyticsChart();
      this.initActiveUsersChart();
    },

    initStoreAnalyticsChart() {
      const ctx = this.$refs.storeAnalyticsChart;
      if (!ctx) return;

      if (this.storeAnalyticsChart) {
        this.storeAnalyticsChart.destroy();
      }

      const isDark = this.isDarkMode;
      const textColor = isDark ? "#d1d5db" : "#9CA3AF";
      const gridColor = isDark
        ? "rgba(75, 85, 99, 0.2)"
        : "rgba(107, 114, 128, 0.1)";

      this.storeAnalyticsChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "Visitors",
              data: [
                12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000,
                40000, 45000, 48000,
              ],
              borderColor: "#8B5CF6",
              backgroundColor: "rgba(139, 92, 246, 0.1)",
              borderWidth: 3,
              fill: true,
              tension: 0.4,
              pointRadius: 6,
              pointBackgroundColor: "#8B5CF6",
              pointBorderColor: isDark ? "#2d3748" : "#fff",
              pointBorderWidth: 2,
            },
            {
              label: "Orders",
              data: [
                8000, 12000, 10000, 15000, 12000, 20000, 18000, 25000, 22000,
                28000, 32000, 35000,
              ],
              borderColor: "#3B82F6",
              backgroundColor: "rgba(59, 130, 246, 0.1)",
              borderWidth: 3,
              fill: true,
              tension: 0.4,
              pointRadius: 6,
              pointBackgroundColor: "#3B82F6",
              pointBorderColor: isDark ? "#2d3748" : "#fff",
              pointBorderWidth: 2,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: false,
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 50000,
              ticks: {
                stepSize: 10000,
                color: textColor,
              },
              grid: {
                color: gridColor,
              },
            },
            x: {
              ticks: {
                color: textColor,
              },
              grid: {
                display: false,
              },
            },
          },
        },
      });
    },

    initActiveUsersChart() {
      const ctx = this.$refs.activeUsersChart;
      if (!ctx) return;

      if (this.activeUsersChart) {
        this.activeUsersChart.destroy();
      }

      const isDark = this.isDarkMode;

      this.activeUsersChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: ["Desktop", "Mobile", "Tablet"],
          datasets: [
            {
              data: [55.6, 33.3, 11.1],
              backgroundColor: ["#8B5CF6", "#FBBF24", "#F87171"],
              borderColor: isDark ? "#2d3748" : "#fff",
              borderWidth: 3,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              display: false,
            },
          },
        },
      });
    },
  },
};
</script>

<style scoped>
.dashboard-container {
  background-color: #f9fafb;
  padding: 0;
  transition: background-color 0.3s ease;
}

.dark-theme {
  background-color: #0f172a;
}

/* KPI Cards */
.stat-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  border: 1px solid #e5e7eb;
}

.dark-theme .stat-card {
  background: #2d3748;
  border-color: #4a5568;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
}

.stat-card:hover {
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
  transform: translateY(-4px);
}

.dark-theme .stat-card:hover {
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
}

.stat-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
  flex-shrink: 0;
}

.stat-icon.product-views {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-icon.product-sold {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-icon.total-likes {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-icon.conversation {
  background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
}

.stat-content {
  flex: 1;
}

.stat-value {
  font-size: 24px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 4px 0;
  transition: color 0.3s ease;
}

.dark-theme .stat-value {
  color: #f3f4f6;
}

.stat-label {
  font-size: 14px;
  color: #6b7280;
  margin: 0;
  transition: color 0.3s ease;
}

.dark-theme .stat-label {
  color: #9ca3af;
}

.stat-change {
  display: inline-block;
  font-size: 13px;
  font-weight: 600;
  margin-top: 8px;
}

.stat-change.positive {
  color: #10b981;
}

.stat-change.negative {
  color: #ef4444;
}

.stat-change i {
  margin-right: 4px;
}

/* Dashboard Cards */
.dashboard-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
  border: 1px solid #e5e7eb;
  height: 100%;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: all 0.3s ease;
}

.dark-theme .dashboard-card {
  background: #2d3748;
  border-color: #4a5568;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
}

.card-header {
  padding: 20px;
  border-bottom: 1px solid #f3f4f6;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: border-color 0.3s ease;
}

.dark-theme .card-header {
  border-bottom-color: #4a5568;
}

.card-title {
  font-size: 16px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
  transition: color 0.3s ease;
}

:global(.dark-theme) .card-title {
  color: #f3f4f6;
}

.card-body {
  padding: 20px;
  flex: 1;
  overflow-y: auto;
}

.card-footer {
  padding: 20px;
  border-top: 1px solid #f3f4f6;
  transition: border-color 0.3s ease;
}

.dark-theme .card-footer {
  border-top-color: #4a5568;
}

/* Chart Legend */
.chart-legend {
  display: flex;
  gap: 16px;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: #6b7280;
  transition: color 0.3s ease;
}

.dark-theme .legend-item {
  color: #9ca3af;
}

.legend-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.legend-dot.visitors {
  background-color: #8b5cf6;
}

.legend-dot.orders {
  background-color: #3b82f6;
}

/* User Stats */
.user-stats {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #6b7280;
}

.stat-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}

/* Location Map */
.location-map {
  margin-bottom: 20px;
}

.world-map {
  width: 100%;
  height: 200px;
}

.country-name {
  font-size: 12px;
  font-weight: 600;
  fill: #1f2937;
  transition: fill 0.3s ease;
}

.dark-theme .country-name {
  fill: #f3f4f6;
}

.percentage {
  font-size: 11px;
  fill: #6b7280;
  font-weight: 500;
  transition: fill 0.3s ease;
}

.dark-theme .percentage {
  fill: #9ca3af;
}

.location-stats {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.location-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px;
  background: #f9fafb;
  border-radius: 6px;
  font-size: 13px;
  color: #374151;
  transition: all 0.3s ease;
}

.dark-theme .location-item {
  background: #374151;
  color: #d1d5db;
}

.location-item .flag {
  font-size: 16px;
}

/* Products Section */
.period-toggle {
  display: flex;
  gap: 8px;
}

.period-btn {
  background: none;
  border: none;
  padding: 6px 12px;
  font-size: 12px;
  color: #9ca3af;
  cursor: pointer;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.dark-theme .period-btn {
  color: #6b7280;
}

.period-btn.active {
  background: #f3f4f6;
  color: #3b82f6;
  font-weight: 600;
}

.dark-theme .period-btn.active {
  background: #4a5568;
  color: #60a5fa;
}

.product-showcase {
  display: flex;
  align-items: center;
  gap: 16px;
}

.product-image {
  width: 100px;
  height: 100px;
  border-radius: 8px;
  overflow: hidden;
  background: #f3f4f6;
  flex-shrink: 0;
  transition: background 0.3s ease;
}

.dark-theme .product-image {
  background: #4a5568;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-info {
  flex: 1;
}

.product-name {
  font-size: 14px;
  font-weight: 700;
  color: #1f2937;
  margin: 0 0 6px 0;
  transition: color 0.3s ease;
}

.dark-theme .product-name {
  color: #f3f4f6;
}

.product-offer {
  font-size: 13px;
  color: #6b7280;
  margin: 0 0 8px 0;
  transition: color 0.3s ease;
}

.dark-theme .product-offer {
  color: #9ca3af;
}

.product-offer strong {
  color: #ef4444;
}

.product-rating {
  display: flex;
  gap: 2px;
  color: #fbbf24;
}

.product-rating i {
  font-size: 14px;
}

/* Metrics Grid */
.email-count {
  font-size: 12px;
  color: #9ca3af;
}

.visitor-count {
  font-size: 12px;
  color: #9ca3af;
}

.metrics-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 16px;
}

.metric-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: #f9fafb;
  border-radius: 8px;
  transition: background 0.3s ease;
}

.dark-theme .metric-item {
  background: #374151;
}

.metric-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 20px;
  flex-shrink: 0;
}

.metric-icon.retargeted {
  background: #e0d5ff;
  color: #a855f7;
}

.dark-theme .metric-icon.retargeted {
  background: rgba(168, 85, 247, 0.2);
  color: #d8b4fe;
}

.metric-icon.opened {
  background: #d1fae5;
  color: #10b981;
}

.dark-theme .metric-icon.opened {
  background: rgba(16, 185, 129, 0.2);
  color: #6ee7b7;
}

.metric-icon.clicked {
  background: #dbeafe;
  color: #3b82f6;
}

.dark-theme .metric-icon.clicked {
  background: rgba(59, 130, 246, 0.2);
  color: #93c5fd;
}

.metric-icon.complaints {
  background: #fecaca;
  color: #ef4444;
}

.dark-theme .metric-icon.complaints {
  background: rgba(239, 68, 68, 0.2);
  color: #fca5a5;
}

.metric-info {
  flex: 1;
}

.metric-info h6 {
  font-size: 13px;
  font-weight: 600;
  color: #1f2937;
  margin: 0 0 4px 0;
  transition: color 0.3s ease;
}

.dark-theme .metric-info h6 {
  color: #f3f4f6;
}

.metric-value {
  font-size: 16px;
  font-weight: 700;
  color: #374151;
  margin: 0;
  transition: color 0.3s ease;
}

.dark-theme .metric-value {
  color: #e5e7eb;
}

.metric-change {
  font-size: 12px;
  font-weight: 600;
  margin-top: 4px;
  display: flex;
  align-items: center;
  gap: 4px;
}

.metric-change.positive {
  color: #10b981;
}

.metric-change.negative {
  color: #ef4444;
}

/* Source Visitors */
.source-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.source-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: #f9fafb;
  border-radius: 8px;
  border-left: 4px solid #f3f4f6;
  transition: all 0.3s ease;
}

.dark-theme .source-item {
  background: #374151;
  border-left-color: #4a5568;
}

.source-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 20px;
  flex-shrink: 0;
}

.source-icon.social {
  background: #d1fae5;
  color: #10b981;
}

.dark-theme .source-icon.social {
  background: rgba(16, 185, 129, 0.2);
  color: #6ee7b7;
}

.source-icon.direct {
  background: #e0d5ff;
  color: #a855f7;
}

.dark-theme .source-icon.direct {
  background: rgba(168, 85, 247, 0.2);
  color: #d8b4fe;
}

.source-icon.advt {
  background: #fed7aa;
  color: #f59e0b;
}

.dark-theme .source-icon.advt {
  background: rgba(245, 158, 11, 0.2);
  color: #fcd34d;
}

.source-icon.referral {
  background: #bfdbfe;
  color: #3b82f6;
}

.dark-theme .source-icon.referral {
  background: rgba(59, 130, 246, 0.2);
  color: #93c5fd;
}

.source-info {
  flex: 1;
}

.source-info h6 {
  font-size: 13px;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
  transition: color 0.3s ease;
}

.dark-theme .source-info h6 {
  color: #f3f4f6;
}

.visitor-text {
  font-size: 14px;
  font-weight: 700;
  color: #6b7280;
  margin: 2px 0 0 0;
  transition: color 0.3s ease;
}

.dark-theme .visitor-text {
  color: #9ca3af;
}

.source-item .change {
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 4px;
}

.source-item .change.positive {
  color: #10b981;
}

.source-item .change.negative {
  color: #ef4444;
}

/* Sellers Table */
.table-responsive {
  overflow-x: auto;
}

.sellers-table {
  width: 100%;
  border-collapse: collapse;
}

.sellers-table thead {
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
  transition: all 0.3s ease;
}

.dark-theme .sellers-table thead {
  background: #374151;
  border-bottom-color: #4a5568;
}

.sellers-table th {
  padding: 16px;
  text-align: left;
  font-size: 12px;
  font-weight: 700;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: color 0.3s ease;
}

.dark-theme .sellers-table th {
  color: #9ca3af;
}

.sellers-table td {
  padding: 16px;
  border-bottom: 1px solid #f3f4f6;
  font-size: 13px;
  color: #374151;
  transition: all 0.3s ease;
}

.dark-theme .sellers-table td {
  border-bottom-color: #4a5568;
  color: #d1d5db;
}

.sellers-table tbody tr:hover {
  background: #f9fafb;
}

.dark-theme .sellers-table tbody tr:hover {
  background: #374151;
}

.seller-info h6 {
  font-size: 13px;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
  transition: color 0.3s ease;
}

.dark-theme .seller-info h6 {
  color: #f3f4f6;
}

.seller-info p {
  font-size: 12px;
  color: #9ca3af;
  margin: 2px 0 0 0;
  transition: color 0.3s ease;
}

.dark-theme .seller-info p {
  color: #6b7280;
}

.seller-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.seller-info img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.seller-info h6 {
  font-size: 13px;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.seller-info p {
  font-size: 12px;
  color: #9ca3af;
  margin: 2px 0 0 0;
}

.seller-info .company {
  font-size: 11px;
}

.flag {
  display: inline-block;
  margin-right: 4px;
}

/* Form Elements */
.form-select-sm {
  padding: 6px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  font-size: 12px;
  color: #6b7280;
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.dark-theme .form-select-sm {
  border-color: #4a5568;
  background: #374151;
  color: #9ca3af;
}

.form-select-sm:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.dark-theme .form-select-sm:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
  .stat-card {
    padding: 16px;
  }

  .card-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .metrics-grid {
    grid-template-columns: 1fr 1fr;
  }

  .chart-legend {
    flex-direction: column;
  }

  .sellers-table {
    font-size: 11px;
  }

  .sellers-table th,
  .sellers-table td {
    padding: 12px 8px;
  }
}

@media (max-width: 576px) {
  .stat-card {
    padding: 12px;
    gap: 12px;
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    font-size: 20px;
  }

  .stat-value {
    font-size: 18px;
  }

  .metrics-grid {
    grid-template-columns: 1fr;
  }

  .product-showcase {
    flex-direction: column;
    text-align: center;
  }

  .period-toggle {
    width: 100%;
    justify-content: center;
  }
}
</style>
