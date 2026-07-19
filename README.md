# EmonCMS Custom Plugins

Custom EmonCMS application plugins for monitoring environmental and energy systems in real-time. These plugins provide dashboards for displaying data collected by DataBridge, transforming raw sensor data into actionable insights.

---

⚠️ **DISCLAIMER**: Use this project at your own risk. No guarantees are provided, and no support is available. These plugins are personal projects shared as-is for educational and reference purposes only. Users are responsible for their own implementation, testing, and any consequences arising from use of these plugins.

---

## Project Information

- **Author**: Adilson Dias
- **Type**: EmonCMS Application Plugins
- **Language**: PHP (EmonCMS native), JavaScript/HTML5
- **Compatibility**: EmonCMS v9+ (tested on v10+)
- **License**: MIT License (or CC0 as specified in original)
- **Status**: Ran reliably for 2+ years in a home deployment

## Overview

This repository contains EmonCMS plugins that display real-time data collected by DataBridge. The plugins provide visualizations, historical analysis, and energy flow diagrams for residential solar installations and environmental monitoring systems.

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

Aquarium environment monitoring dashboard with real-time water quality tracking and alerting capabilities.

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

Solar generation, consumption, and battery monitoring system with real-time power graphs and historical analysis.

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

| Flow Path       | Description             | Status |
| ---------------- | ------------------------- | ------ |
| Solar → House   | Direct self-consumption | Green  |
| Solar → Battery | Charging storage        | Blue   |
| Solar → Grid    | Export excess           | Yellow |
| Battery → House | Discharge during night  | Purple |
| Grid → House    | Import when needed      | Red    |
| Grid → Battery  | Charging from grid      | Orange |

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

The plugin includes feed matching:

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

| Requirement         | Specification                       |
| --------------------- | -------------------------------------- |
| **EmonCMS Version** | 9.0+ (tested on 10+)                |
| **PHP Version**     | 7.4+                                |
| **Database**        | MySQL 5.7+ or MariaDB               |
| **Feed Engine**     | PHPTimeSeries or MySQL              |
| **Graph Module**    | EmonCMS Graph module (for charting) |
| **Storage**         | 100MB minimum                       |

### Feed Requirements

- At least 4 input feeds configured (solar + house + battery)
- Feeds must be actively receiving data
- Input feeds required for real-time view
- Time-series (kWh) feeds optional for historical analysis

### Browser Compatibility

| Browser             | Version | Support      | Notes                                   |
| --------------------- | --------- | -------------- | ------------------------------------------ |
| Chrome/Edge         | 90+     | ✅ Full       | Native support for EmonCMS Graph module |
| Firefox             | 88+     | ✅ Full       | Full compatibility with Graph rendering |
| Safari              | 14+     | ✅ Full       | All features supported                  |
| Mobile Safari (iOS) | 14+     | ✅ Responsive | Responsive design for tablets/phones    |
| Chrome Mobile       | 90+     | ✅ Responsive | Graph zoom may require pinch-to-zoom    |

---

## Installation

### Step 1: Locate EmonCMS Directory

```
# Typical location on Linux
/var/www/emoncms/

# Or find it
sudo find / -name "emoncms" -type d 2>/dev/null
```

### Step 2: Clone or Copy Plugins

**Option A: Using Git Clone**

```
cd /var/www/emoncms/Modules/app/
git clone https://github.com/adilsondias-engineer/emoncms_plugins.git temp
mv temp/myfishtank ./
mv temp/mysolarpvbattery ./
rm -rf temp
```

**Option B: Manual Copy**

```
sudo cp -r myfishtank /var/www/emoncms/Modules/app/
sudo cp -r mysolarpvbattery /var/www/emoncms/Modules/app/
sudo chown -R www-data:www-data /var/www/emoncms/Modules/app/myfishtank
sudo chown -R www-data:www-data /var/www/emoncms/Modules/app/mysolarpvbattery
```

### Step 3: Verify EmonCMS Graph Module

```
ls -la /var/www/emoncms/Modules/graph/

# If not installed, clone it
cd /var/www/emoncms/Modules/
git clone https://github.com/emoncms/graph.git
sudo chown -R www-data:www-data graph/
```

Verify in EmonCMS: **Setup** → **Modules** → confirm "graph" appears with status "OK"/"Installed"

```
sudo rm -rf /var/www/emoncms/Modules/cache/*
sudo systemctl restart apache2
# or: sudo systemctl restart nginx
```

### Step 4: Verify Installation

1. Log into EmonCMS dashboard
2. Go to **Apps** menu
3. You should see "My Fish Tank" and "My Solar PV Battery"

## Configuration

### Creating a Dashboard: My Fish Tank

1. Go to EmonCMS **Dashboard** → **Edit Dashboard** → **Add Widget** → search "My Fish Tank"
2. Click the configuration icon and select your configured feeds
3. Click **Save**

### Creating a Dashboard: My Solar PV Battery

1. Ensure feeds are being populated by DataBridge
2. **Dashboard** → **Edit Dashboard** → **Add Widget** → select "My Solar PV Battery"
3. Configure feeds (Auto-Configure recommended, or manual override)
4. Click **Save**

## Troubleshooting

### Plugin Not Visible in Apps Menu

- Check permissions: `sudo chown -R www-data:www-data /var/www/emoncms/Modules/app/myfishtank/`
- Clear cache: `sudo rm -rf /var/www/emoncms/Modules/cache/*`
- Restart web server: `sudo systemctl restart apache2`
- Verify file structure: `ls /var/www/emoncms/Modules/app/myfishtank/`

### "No Data Available" in Plugin

- Verify feeds are receiving data (Feeds page, last update < 1 minute)
- Check widget feed selection / try "Auto-Configure"
- Verify feed values are numeric, not NULL

### Graph Not Displaying

- Check browser console (F12) for CORS/404/undefined errors
- Verify EmonCMS Graph module is installed and enabled
- Verify historical feeds exist for the History view
- Clear browser cache

### Feeds Show Old Data

- Check DataBridge MQTT publishing: `mosquitto_sub -h localhost -t "emon/#"`
- Verify EmonCMS Input page shows recent timestamps
- Check MySQL: `SELECT * FROM feeds WHERE name='solar' ORDER BY updated DESC LIMIT 1;`
- Restart DataBridge, hard-refresh browser

### Performance Issues

- Increase refresh interval (5s → 10s/30s)
- Limit chart history window (6h → 1h/3h)
- Check system resources: `free -h`

## Development & Customization

### Plugin Structure

```
myfishtank/
├── myfishtank.php      # Main application logic
├── app.json            # Plugin metadata
└── README.md           # Documentation
```

### Modifying Display Format

Edit `Views/app.html` to change displayed parameters, layout, styling, refresh interval.

### Adding Custom Calculations

Edit `Views/app.js` for custom value transformations, alert logic, and integration with the EmonCMS Graph module APIs.

## Integration with DataBridge

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
- [ ] Integration with home automation (Home Assistant, OpenHAB)
- [ ] Webhook notifications for events
- [ ] Custom formula builder for derived metrics
- [ ] Dark mode theme

## Related Projects

- **[DataBridge](https://github.com/adilsondias-engineer/DataBridge)** - Data collection service
- **[Solar Energy Monitor (Java)](https://github.com/adilsondias-engineer/SolarEnergyMonitor)** - Desktop analysis tool
- **[EmonCMS](https://github.com/emoncms/emoncms)** - Open Energy Monitor platform

## Credits

- **EmonCMS**: Open-source energy monitoring platform
- **Chart.js**: JavaScript charting library

## License

These plugins are provided as-is for use with EmonCMS. See individual plugin directories for specific license terms.

## Support & Community

- **EmonCMS Forums**: <https://community.openenergymonitor.org/>
- **GitHub Issues**: Report bugs on this repository

## Author & Contact

**Adilson Dias**
[GitHub](https://github.com/adilsondias-engineer)

---

*EmonCMS Custom Plugins: real-time energy and environmental monitoring dashboards.*
