pipeline {
  agent {
    dockerfile {
      filename 'Dockerfile'
    }
  }
  stages {
    stage('Build') {
      stage('Composer') {
        steps {
          sh 'composer install'
        }
      }
    }
    stage('Test') {
      stage('Unit + Integration') {
        steps {
          sh 'npm run test'
        }
      }
    }
  }
  options {
    buildDiscarder(logRotator(numToKeepStr: '2'))
  }
}