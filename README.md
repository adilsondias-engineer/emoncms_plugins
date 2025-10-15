# EmonCMS Custom Plugins

Custom EmonCMS application plugins for monitoring environmental and energy systems in real-time. These plugins provide sophisticated dashboards for displaying data collected by DataBridge, transforming raw sensor data into actionable insights.

> **TML** stands for **Tiny Memories Laser**, a registered business (API-led Pty Ltd, ABN) that operated from 2018-2024. These EmonCMS plugins represent the visualization layer of the TML IoT monitoring ecosystem, providing beautiful real-time dashboards for home energy and environmental monitoring.

---

⚠️ **DISCLAIMER**: Use this project at your own risk. No guarantees are provided, and no support is available. These plugins are personal projects shared as-is for educational and reference purposes only. Users are responsible for their own implementation, testing, and any consequences arising from use of these plugins.

---

## Project Information

- **Author**: Adilson Dias (API-Led Pty Ltd → Tiny Memories Laser (TML))
- **Type**: EmonCMS Application Plugins
- **Language**: PHP (EmonCMS native), JavaScript/HTML5
- **Compatibility**: EmonCMS v9+ (tested on v10+)
- **License**: MIT License (or CC0 as specified in original)
- **Status**: Production-ready (2+ years operational)

## Overview

This repository contains professional-grade EmonCMS plugins that display real-time data collected by DataBridge. The plugins provide sophisticated visualizations, historical analysis, and energy flow diagrams for residential solar installations and environmental monitoring systems.

### Integration Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    DataBridge (Python)                       │
│  Collects from: Solar Inverter, Energy Meter, pH/TDS Sensor │
└────────────────────────────┬────────────────────────────────┘
                             │
                    ┌────────┴────────┐
                    ↓                 ↓
            [MQTT Publishing]   [MySQL Storage]
            (Real-time events) (Historical data)
                    ↓                 ↓
┌───────────────────┴────────────────────────────────────────┐
│                    EmonCMS Server                            │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Custom Plugins (This Repository)                    │  │
│  ├──────────────────────────────────────────────────────┤  │
│  │  ✓ My Fish Tank Plugin (pH/TDS/Temp monitoring)     │  │
│  │  ✓ My Solar PV Battery Plugin (Energy flows)        │  │
│  └──────────────────────────────────────────────────────┘  │
│  Dashboard | API | User Management | Feed Management       │
└─────────────────────────────────┬─────────────────────────┘
                                  │
                    ┌─────────────┴─────────────┐
                    ↓                           ↓
            [Web Browser]              [Mobile Device]
            (Real-time dashboard)      (Remote monitoring)
```

## Plugins Included

### 1. My Fish Tank (`myfishtank`)

Professional aquarium environment monitoring dashboard with real-time water quality tracking and alerting capabilities.

#### Features

**Real-Time Monitoring**
- Current water temperature with threshold visualization
- Temperature alerts (Low/High thresholds)
- TDS (Total Dissolved Solids) tracking - PPM scale
- pH Level precision - 0.01 increments
- EC (Electrical Conductivity) - US scale
- Salinity - PPM measurement
- Specific Gravity (S.G.) - proportional scale

**Display Capabilities**
- 8 water quality parameters in clean table layout
- Large, easy-to-read values for quick assessment
- Color-coded alerts for out-of-range parameters
- Auto-refresh every 5 seconds
- Mobile-responsive design

**Use Cases**
- Saltwater aquariums (reef tanks)
- Freshwater planted tanks
- Aquaponics systems
- Hydroponics environments
- Water treatment monitoring

#### Feed Mapping

The plugin expects these EmonCMS feeds (configure in plugin settings):

```
Feed Name              │ Scale Factor │ Unit   │ Description
─────────────────────┼──────────────┼────────┼──────────────────────
currentTemp          │ ×0.1         │ °C     │ Current water temperature
highTemp             │ ×0.1         │ °C     │ High temp threshold
lowTemp              │ ×0.1         │ °C     │ Low temp threshold
tds                  │ ×1.0         │ PPM    │ Total dissolved solids
ph                   │ ×0.01        │ pH     │ Acidity/alkalinity
ec                   │ ×1.0         │ US/cm  │ Electrical conductivity
salinity             │ ×1.0         │ PPM    │ Salt concentration
sg                   │ ×0.001       │ S.G.   │ Specific gravity
```

#### Configuration Example

In EmonCMS plugin settings:

```
Temperature Inputs:
  Current Temperature Feed: [Select: currentTemp]
  High Threshold Feed: [Select: highTemp]
  Low Threshold Feed: [Select: lowTemp]

Water Quality Inputs:
  TDS Feed: [Select: tds]
  pH Feed: [Select: ph]
  EC Feed: [Select: ec]
  Salinity Feed: [Select: salinity]
  S.G. Feed: [Select: sg]

Display Settings:
  ☑ Show thresholds
  ☑ Color-code alerts
  Refresh interval: 5 seconds
```

---

### 2. My Solar PV Battery (`mysolarpvbattery`)

Advanced solar generation, consumption, and battery monitoring system with real-time power graphs and sophisticated historical analysis.

#### Features

**Real-Time Power Monitoring**
- Solar generation display (kW or W)
- House consumption tracking
- Grid import/export balance (positive = export)
- Battery charging/discharging status
- Battery state of charge (SOC) percentage
- Battery time remaining calculation
- Live power graph updates every 5 seconds

**Interactive Power Graph**
- Uses EmonCMS Graph module for rendering
- 6-hour default time window
- Zoom and pan capabilities with native EmonCMS controls
- Multiple view options: 1h, 3h, 6h, 24h
- Stacked area chart visualization
- Color-coded by source/destination
- Hover for detailed values

**Historical Analysis Dashboard**
- Daily bar graph view with trend analysis
- Solar self-consumption percentage
- Grid export/import balance tracking
- Battery charge/discharge cycle analysis
- Clickable daily details with hourly breakdown
- Monthly aggregation and comparison

**Energy Flow Visualization**
```
                 ☀️ Solar
                  ↓
        ┌─────────┼─────────┐
        ↓         ↓         ↓
     🏠 House  🔋 Battery  📊 Grid Export
        ↓         ↓
      [Consumption] [Storage]
```

#### Power Flow Tracking

The plugin calculates and displays:

| Flow Path | Description | Status |
|-----------|-------------|--------|
| Solar → House | Direct self-consumption | Green |
| Solar → Battery | Charging storage | Blue |
| Solar → Grid | Export excess | Yellow |
| Battery → House | Discharge during night | Purple |
| Grid → House | Import when needed | Red |
| Grid → Battery | Charging from grid | Orange |

#### Feed Configuration

**Required Real-Time Power Feeds (Watts):**

```
Feed Name          │ Unit │ Description
─────────────────┼──────┼──────────────────────────
use (import)     │ W    │ House consumption
solar            │ W    │ Solar PV generation
battery_charge   │ W    │ Battery charging power
battery_discharge│ W    │ Battery discharging power
```

**Optional Real-Time Feeds:**

```
Feed Name        │ Unit │ Description
────────────────┼──────┼──────────────────────────
battery_power   │ W    │ Combined battery power
battery_soc     │ %    │ State of charge 0-100%
```

**Optional Historical Feeds (kWh - for History view):**

```
Feed Name              │ Unit │ Description
──────────────────────┼──────┼──────────────────────────
use_kwh              │ kWh  │ Cumulative consumption
solar_kwh            │ kWh  │ Cumulative generation
solar_direct_kwh     │ kWh  │ Direct self-consumption
import_kwh           │ kWh  │ Grid imports
battery_charge_kwh   │ kWh  │ Energy charged to battery
battery_discharge_kwh│ kWh  │ Energy discharged
```

#### Configuration Options

**Display Settings**
```
Power Display Unit:
  ☐ Show in Watts (W)
  ☑ Show in kilowatts (kW)

Battery Settings:
  Battery Capacity: [___] kWh    (enables time-remaining calculation)
  Min SOC Threshold: [__] %      (for alerts)
  Max SOC Threshold: [__] %      (for alerts)

Chart Settings:
  Default time window: 6 hours
  Refresh interval: 5 seconds
  ☑ Show stacked area chart
  ☑ Show daily summary
  ☑ Show monthly comparison
```

#### Feed Auto-Configuration

The plugin includes intelligent feed matching:

1. Open plugin configuration
2. Click "Auto-Configure Feeds"
3. Plugin searches for feeds matching common naming patterns
4. Selects appropriate feeds automatically
5. Manual override available for custom feed names

#### Advanced Features

**Battery Time Remaining Calculation**
```
Time Remaining = (Current SOC - Min SOC) × Battery Capacity / Discharge Power
```

Example: 85% SOC - 20% min SOC = 65% usable = 7.5kWh ÷ 3kW discharge = 2.5 hours

**Daily Self-Consumption Analysis**
```
Self-Consumption % = Direct Solar Use / Total Solar Generation × 100
```

**ROI & Payback Analysis** (when historical data available)
```
Daily Savings = (Grid Import Cost - Grid Export Revenue)
Monthly ROI = Daily Savings × 30
Annual Payback = System Cost / Annual Savings
```

---

## System Requirements

### EmonCMS Installation

| Requirement | Specification |
|-------------|---------------|
| **EmonCMS Version** | 9.0+ (tested on 10+) |
| **PHP Version** | 7.4+ |
| **Database** | MySQL 5.7+ or MariaDB |
| **Feed Engine** | PHPTimeSeries or MySQL |
| **Graph Module** | EmonCMS Graph module (for charting) |
| **Storage** | 100MB minimum |

### Feed Requirements

- At least 4 input feeds configured (solar + house + battery)
- Feeds must be actively receiving data
- Input feeds required for real-time view
- Time-series (kWh) feeds optional for historical analysis

### Browser Compatibility

| Browser | Version | Support | Notes |
|---------|---------|---------|-------|
| Chrome/Edge | 90+ | ✅ Full | Native support for EmonCMS Graph module |
| Firefox | 88+ | ✅ Full | Full compatibility with Graph rendering |
| Safari | 14+ | ✅ Full | All features supported |
| Mobile Safari (iOS) | 14+ | ✅ Responsive | Responsive design for tablets/phones |
| Chrome Mobile | 90+ | ✅ Responsive | Graph zoom may require pinch-to-zoom |

---

## Installation

### Step 1: Locate EmonCMS Directory

Find your EmonCMS installation:

```bash
# Typical location on Linux
/var/www/emoncms/

# Or find it
sudo find / -name "emoncms" -type d 2>/dev/null
```

### Step 2: Clone or Copy Plugins

**Option A: Using Git Clone**

```bash
cd /var/www/emoncms/Modules/app/

git clone https://github.com/adilsondias-engineer/emoncms-custom-plugins.git temp

mv temp/myfishtank ./
mv temp/mysolarpvbattery ./

rm -rf temp
```

**Option B: Manual Copy**

```bash
# Copy plugin folders to EmonCMS app directory
sudo cp -r myfishtank /var/www/emoncms/Modules/app/
sudo cp -r mysolarpvbattery /var/www/emoncms/Modules/app/

# Adjust permissions
sudo chown -R www-data:www-data /var/www/emoncms/Modules/app/myfishtank
sudo chown -R www-data:www-data /var/www/emoncms/Modules/app/mysolarpvbattery
```

### Step 3: Verify EmonCMS Graph Module

The solar plugin requires the EmonCMS Graph module for charting:

```bash
# Verify Graph module is installed
ls -la /var/www/emoncms/Modules/graph/

# If not installed, clone it
cd /var/www/emoncms/Modules/
git clone https://github.com/emoncms/graph.git

# Adjust permissions
sudo chown -R www-data:www-data graph/
```

Verify in EmonCMS:
1. Go to **Setup** → **Modules**
2. Confirm "graph" appears in module list
3. Status should show "OK" or "Installed"

```bash
# Clear EmonCMS cache
sudo rm -rf /var/www/emoncms/Modules/cache/*

# Restart web server
sudo systemctl restart apache2
# or for nginx
sudo systemctl restart nginx
```

### Step 5: Verify Installation

1. Log into EmonCMS dashboard
2. Go to **Apps** menu
3. You should see:
   - "My Fish Tank"
   - "My Solar PV Battery"

If not visible, check:
```bash
# Verify files exist
ls -la /var/www/emoncms/Modules/app/myfishtank/
ls -la /var/www/emoncms/Modules/app/mysolarpvbattery/

# Check permissions
sudo ls -la /var/www/emoncms/Modules/app/ | grep my
```

---

## Configuration

### Creating a Dashboard: My Fish Tank

#### Step 1: Add Plugin to Dashboard

1. Go to EmonCMS **Dashboard** page
2. Click **Edit Dashboard** (pencil icon)
3. Click **Add Widget**
4. Search for "My Fish Tank"
5. Click to add

#### Step 2: Configure Feeds

1. Click the **configuration icon** (wrench/gear) on the widget
2. Select your configured feeds:
   - Current Temperature → Select "currentTemp" feed
   - High Temperature → Select "highTemp" feed
   - Low Temperature → Select "lowTemp" feed
   - TDS → Select "tds" feed
   - pH → Select "ph" feed
   - EC → Select "ec" feed
   - Salinity → Select "salinity" feed
   - S.G. → Select "sg" feed
3. Click **Save**

#### Step 3: Set Temperature Thresholds

In EmonCMS, create input feeds for thresholds (if using dynamic thresholds):

```
Input Process Chain:
├─ Read value from device
├─ Log to feed: highTemp
├─ (or) Fixed value: 30°C for high temp
└─ Fixed value: 20°C for low temp
```

### Creating a Dashboard: My Solar PV Battery

#### Step 1: Prepare Your Feeds

Ensure you have feeds being populated by DataBridge:

```
DataBridge → MQTT → EmonCMS Input Processing
   ↓            ↓
[Solar Power]  [MQTT/solar] → Log to feed: solar
[House Use]    [MQTT/use]   → Log to feed: use
[Battery +]    [MQTT/batt_charge] → Log to feed: battery_charge
[Battery -]    [MQTT/batt_discharge] → Log to feed: battery_discharge
```

**Configure Input Processes in EmonCMS:**

Navigate to **Inputs** → Select your MQTT input → **Edit Process Chain**

Add processes for each feed:
```
1. Log to Feed → "solar" (W feed, kW type)
2. Accumulate → "solar_kwh" (for daily totals)
```

#### Step 2: Add Widget to Dashboard

1. Go to **Dashboard** → **Edit Dashboard**
2. Click **Add Widget**
3. Select "My Solar PV Battery"
4. Click **Save**

#### Step 3: Configure Solar Plugin

1. Click the configuration icon on the widget
2. **Auto-Configure** (recommended):
   - Click "Auto-Configure Feeds"
   - Review suggestions
   - Click "Accept"

3. **Manual Configuration** (if auto-configure doesn't work):

```
Real-Time Feeds:
  House Consumption: [Select: use]
  Solar Generation: [Select: solar]
  Battery Charging: [Select: battery_charge]
  Battery Discharging: [Select: battery_discharge]

Optional:
  Battery Power: [Select: battery_power] (or leave blank)
  Battery SOC: [Select: battery_soc] (or calculated from charger)

Display Options:
  ☑ Show in kilowatts (kW)
  Battery Capacity: 11.8 kWh
  Time window default: 6 hours
  Refresh interval: 5 seconds
```

#### Step 4: Add Historical Views (Optional)

For historical analysis, configure kWh feeds:

```
Input Process for Solar (Daily Totals):
├─ From MQTT: solar (watts)
├─ Accumulate: solar_kwh (kWh)
└─ Log every 60 seconds
```

Repeat for:
- use → use_kwh
- battery_charge → battery_charge_kwh
- battery_discharge → battery_discharge_kwh

---

## Feed Mapping Guide

### Simple Setup (Real-Time Only)

**Minimum 4 feeds needed:**

```
EmonCMS Dashboard
├─ My Solar PV Battery Widget
│  ├─ Input 1: solar (kW) ← DataBridge publishes current solar power
│  ├─ Input 2: use (kW) ← Current house consumption
│  ├─ Input 3: battery_charge (kW) ← Power charging battery
│  └─ Input 4: battery_discharge (kW) ← Power from battery
│
└─ My Fish Tank Widget
   ├─ Input 1: currentTemp (°C)
   ├─ Input 2: ph (pH)
   ├─ Input 3: tds (PPM)
   ├─ Input 4: highTemp (°C)
   ├─ Input 5: lowTemp (°C)
   ├─ Input 6: ec (US/cm)
   ├─ Input 7: salinity (PPM)
   └─ Input 8: sg (S.G.)
```

### Advanced Setup (Real-Time + Historical)

**For complete energy analysis:**

```
Real-Time (Power in W):
├─ solar (W)
├─ use (W)
├─ battery_charge (W)
├─ battery_discharge (W)
├─ battery_soc (%)
└─ battery_power (W)

Historical (Energy in kWh):
├─ solar_kwh
├─ use_kwh
├─ solar_direct_kwh (solar used directly)
├─ import_kwh (grid import)
├─ battery_charge_kwh
└─ battery_discharge_kwh
```

---

## Troubleshooting

### Plugin Not Visible in Apps Menu

**Problem**: Plugin folders copied but not appearing in EmonCMS

**Solutions**:

1. **Check file permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/emoncms/Modules/app/myfishtank/
   sudo chown -R www-data:www-data /var/www/emoncms/Modules/app/mysolarpvbattery/
   ```

2. **Clear cache:**
   ```bash
   sudo rm -rf /var/www/emoncms/Modules/cache/*
   ```

3. **Restart web server:**
   ```bash
   sudo systemctl restart apache2
   ```

4. **Check file structure:**
   ```bash
   ls /var/www/emoncms/Modules/app/myfishtank/
   # Should contain: app.php, manifest.json, and other files
   ```

### "No Data Available" in Plugin

**Problem**: Widget loads but shows no data

**Solutions**:

1. **Verify feeds are receiving data:**
   - Go to EmonCMS **Feeds** page
   - Check each configured feed has recent data (last update < 1 minute)
   - If not: Check DataBridge is running and publishing

2. **Check feed selection:**
   - Click widget configuration icon
   - Verify correct feeds are selected
   - Try "Auto-Configure" if available

3. **Verify feed format:**
   - Feed should contain numeric values
   - Check for NULL or non-numeric values in feed data

4. **Debug:**
   ```bash
   # Check EmonCMS logs
   tail -f /var/log/emoncms/emoncms.log
   ```

### Graph Not Displaying

**Problem**: Plugin loads but interactive graphs are blank

**Solutions**:

1. **Check JavaScript console:**
   - Press F12 in browser
   - Check **Console** tab for errors
   - Look for "CORS", "404", or "undefined" errors

2. **Verify EmonCMS Graph module is installed:**
   - Go to EmonCMS **Setup** → **Modules**
   - Ensure "Graph" module is listed and enabled
   - If missing: Clone from https://github.com/emoncms/graph

3. **Verify historical feeds:**
   - Historical view requires kWh feeds
   - Real-time view only needs W feeds
   - Check feeds contain sufficient data points

4. **Clear browser cache:**
   ```bash
   # Or in browser: Ctrl+Shift+Delete → Clear Cache
   ```

5. **Check EmonCMS Graph rendering:**
   - Go to **Inputs** → Select any feed
   - View → **Graph**
   - If graph doesn't display: EmonCMS Graph module issue, not plugin

### Feeds Show Old Data

**Problem**: Plugin displaying stale values despite recent DataBridge collection

**Solutions**:

1. **Check DataBridge MQTT publishing:**
   ```bash
   # Subscribe to MQTT topics (if using MQTT)
   mosquitto_sub -h localhost -t "emon/#"
   # Should see current values
   ```

2. **Verify EmonCMS receiving data:**
   - EmonCMS Input page should show recent timestamps
   - If not: DataBridge → MQTT → EmonCMS chain broken

3. **Check MySQL data:**
   ```bash
   mysql> SELECT * FROM feeds WHERE name='solar' ORDER BY updated DESC LIMIT 1;
   # Should show recent timestamp
   ```

4. **Force refresh:**
   - Restart DataBridge
   - In EmonCMS, click **Refresh** button
   - Hard-refresh browser: Ctrl+F5

### Widget Configuration Disappeared

**Problem**: Widget settings are not saved after closing

**Solutions**:

1. **Check browser storage:**
   - Clear browser cache: Ctrl+Shift+Delete
   - Try different browser

2. **Verify permissions:**
   - Ensure logged in as user with write permissions

3. **Check feed access:**
   - Logged-in user must have access to selected feeds
   - Go to Feeds → Check feed permissions

### Performance Issues

**Problem**: Dashboard loading slowly or widgets lagging

**Solutions**:

1. **Reduce refresh rate:**
   - In widget config, increase refresh interval (default 5s → 10s/30s)
   - Reduces server/network load

2. **Limit chart history:**
   - Change default chart window: 6h → 1h or 3h
   - Less data = faster rendering

3. **Optimize server:**
   ```bash
   # Check system resources
   top
   free -h
   
   # If high CPU: reduce DataBridge poll rates
   # If high memory: check for feed storage issues
   ```

---

## Development & Customization

### Plugin Structure

Each plugin follows EmonCMS conventions:

```
myfishtank/
├── myfishtank.php      # Main application logic
├── app.json            # Plugin metadata
└── README.md           # Documentation
```

The solar plugin specifically integrates with:
- **EmonCMS Graph Module**: For rendering interactive power charts
- **Graph APIs**: `graph_draw()`, `timerange_select()` for user interaction
- **Feed data**: Pulls from configured power feeds to populate graphs

### Modifying Display Format

Edit `Views/app.html` to change:
- Number of displayed parameters
- Table layout and styling
- Refresh interval
- Alert thresholds display

Example modification:

```html
<!-- Default: Shows all 8 parameters -->
<!-- To hide specific parameter: comment out row -->
<!-- <tr><td>Salinity:</td><td id="salinity">-</td></tr> -->
```

### Adding Custom Calculations

Edit `Views/app.js` to add:
- Custom value transformations
- Alarm/alert logic
- Historical trend analysis (using EmonCMS Graph module APIs)
- Email/webhook notifications
- Integration with EmonCMS Graph module for custom chart rendering

---

## Integration with DataBridge

### Recommended Data Flow

```
1. DataBridge (Python)
   ├─ Collect data from devices
   ├─ Validate measurements
   └─ Publish to outputs:
      ├─ MQTT topic: emon/solar/power → 5000 W
      ├─ MQTT topic: emon/house/use → 2000 W
      ├─ MySQL insert → solarusage table
      └─ EmonCMS HTTP POST → Input API

2. EmonCMS Server
   ├─ Receive from MQTT (real-time)
   ├─ Receive from HTTP/MySQL (historical)
   ├─ Store in feeds
   └─ Process with input scripts

3. EmonCMS Plugins (This Repository)
   ├─ Read from feeds
   ├─ Render dashboards
   ├─ Calculate derived values
   └─ Display to user
```

### DataBridge Configuration for EmonCMS

In `DataBridge/config.toml`:

```toml
[emoncms]
enabled = true
server = "http://localhost/emoncms"
api_key = "YOUR_EMONCMS_API_KEY"
timeout = 5

[mqtt]
enabled = true
broker = "localhost"
port = 1883
topic_prefix = "emon/"
```

---

## Known Limitations

- Real-time graph refresh: 5-second minimum (browser/server limitation)
- Historical analysis limited to configured time windows
- No multi-user dashboard coordination
- Mobile display optimized for landscape view
- No native alerting (use EmonCMS notifications instead)
- Requires EmonCMS v9+ (older versions may not work)

## Future Enhancements

- [ ] Weather data integration (solar forecasting)
- [ ] Email alerts for threshold violations
- [ ] Export data to CSV/PDF
- [ ] Mobile app companion
- [ ] Multi-site dashboard aggregation
- [ ] Machine learning anomaly detection
- [ ] Integration with home automation (Home Assistant, OpenHAB)
- [ ] Webhook notifications for events
- [ ] Custom formula builder for derived metrics
- [ ] Dark mode theme

## Related Projects

- **[DataBridge](https://github.com/adilsondias-engineer/DataBridge)** - Data collection service
- **[Solar Energy Monitor (Java)](https://github.com/adilsondias-engineer/SolarEnergyMonitor)** - Desktop analysis tool
- **[EmonCMS](https://github.com/emoncms/emoncms)** - Open Energy Monitor platform

## Credits

- **EmonCMS**: Excellent open-source energy monitoring platform
- **Chart.js**: Beautiful JavaScript charting library
- **Community**: EmonCMS community forums and contributors

## License

These plugins are provided as-is for use with EmonCMS. See individual plugin directories for specific license terms.

## Support & Community

- **EmonCMS Forums**: https://community.openenergymonitor.org/
- **GitHub Issues**: Report bugs on this repository
- **Documentation**: See README.md in each plugin folder

## Author & Contact

**Adilson Dias**  
Integration Architect | Polyglot Developer  
Tiny Memories Laser (TML)  
[GitHub](https://github.com/adilsondias-engineer) | [LinkedIn](https://linkedin.com/in/adilsondias)

---

*EmonCMS Custom Plugins: Bringing real-time energy and environmental monitoring to your fingertips.*