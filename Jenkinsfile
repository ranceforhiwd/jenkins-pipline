pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                sh: 'git ftp push'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing..'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying. revised....'
            }
        }
    }
}